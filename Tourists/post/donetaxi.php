<?php
session_start();
require '../../conn.php';
require '../nav.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;

    if ($id) {
        // Update query
        $updateQuery = "UPDATE emergency SET status = 'Done' WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            http_response_code(500);
            echo "error: " . $conn->error;
        }

        $stmt->close();
    } else {
        http_response_code(400);
        echo "Invalid request.";
    }

    $conn->close();
    exit;
}

$tourist_email = $_SESSION['email'];

$trippost = "SELECT td.*, g.name AS gname, g.phone AS gphone FROM emergency td 
             LEFT JOIN guiders g ON td.guider_mail = g.email 
             WHERE status ='Done' AND tourist_mail = ?";
$stmt = $conn->prepare($trippost);
$stmt->bind_param("s", $tourist_email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Jobs</title>
    <link rel="stylesheet" href="done.css">
</head>
<body>
    <div class="container">
        <div class="top">
            <h1>Completed Taxi Roots</h1>
            <button class="btn" onclick="window.location.href='yourtaxiposts.php'">Back</button>
        </div>

        <table>
            <thead>
                <tr>
                    <!-- <th>Client Name</th> -->
                    <th>Team Number</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Destination</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <!-- <td data-cell="Name"><?php echo htmlspecialchars($row['name']); ?></td> -->
                            <td data-cell="Team Number"><?php echo htmlspecialchars($row['team_number']); ?></td>
                            <td data-cell="Phone"><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td data-cell="Address"><?php echo htmlspecialchars($row['address']); ?></td>
                            <td data-cell="Destination"><?php echo htmlspecialchars($row['destination']); ?></td>
                            <td data-cell="Posted Date"><?php echo htmlspecialchars($row['created_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No completed jobs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function updateJobStatus(button) {
            const jobId = button.getAttribute("data-id");

            if (confirm("Are you sure you want to mark this job as done?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "donejob.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        alert("Job marked as done!");
                        window.location.reload();
                    } else {
                        alert("An error occurred: " + xhr.responseText);
                    }
                };
                xhr.send("id=" + jobId);
            }
        }
    </script>
</body>
</html>
