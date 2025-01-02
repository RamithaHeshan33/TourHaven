<?php
    session_start();
    require '../../conn.php';
    require '../nav.php';

    $guider_email = $_SESSION['email'];

    // Fetching guider's taken jobs
    $tookjobs = "SELECT * FROM trip_details WHERE guider_mail = ? AND status = 'Pending'";
    $stmt = $conn->prepare($tookjobs);
    $stmt->bind_param("s", $guider_email);
    $stmt->execute();
    $result = $stmt->get_result();
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taken Jobs</title>
    <link rel="stylesheet" href="tookjobs.css">
    <link rel="stylesheet" href="findjob.css">
</head>
<body>
    <div class="container">
        <div class="top">
            <h1>Your Taken Jobs</h1>
            <button class="btn" onclick="window.location.href='donejob.php'">Done Jobs</button>
        </div>
        <table>
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>Client Name</th>
                    <th>Team Number</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Destination</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Remarks</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td style="display: none;"><?php echo htmlspecialchars($row['id']); ?></td>
                            <td data-cell="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td data-cell="Team Number"><?php echo htmlspecialchars($row['team_number']); ?></td>
                            <td data-cell="Phone"><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td data-cell="Addres"><?php echo htmlspecialchars($row['address']); ?></td>
                            <td data-cell="Destination"><?php echo htmlspecialchars($row['destination']); ?></td>
                            <td data-cell="Start Date"><?php echo htmlspecialchars($row['st_date']); ?></td>
                            <td data-cell="End Date"><?php echo htmlspecialchars($row['end_date']); ?></td>
                            <td data-cell="Remakes"><?php echo htmlspecialchars($row['remakes']); ?></td>
                            <td data-cell="Posted Date"><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td data-cell="Action">
                                <button class="update-btn" data-id="<?php echo $row['id']; ?>" onclick="showModal(this)">Done</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" style="text-align: center;">No jobs taken yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>Are you sure you want to mark this job as "Done"?</p>
            <button id="confirmButton" class="btn-success">Yes</button>
            <button class="btn-danger" onclick="closeModal()">No</button>
        </div>
    </div>


    <script>
        let currentJobId = null;

        function showModal(button) {
            currentJobId = button.getAttribute("data-id");
            document.getElementById("confirmationModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("confirmationModal").style.display = "none";
            currentJobId = null;
        }

        document.getElementById("confirmButton").addEventListener("click", function() {
            if (currentJobId) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "update_job_status.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        window.location.href = "donejob.php";
                    } else {
                        alert("An error occurred. Please try again.");
                    }
                };
                xhr.send("id=" + currentJobId);
            }
        });

    </script>
</body>
</html>

<?php
    $stmt->close();
    $conn->close();
?>
