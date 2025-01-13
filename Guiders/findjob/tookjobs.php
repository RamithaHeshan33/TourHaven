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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4WKu5raw64v4-CB8bYSq7SMtFikfu5lg"></script>
    

</head>
<body>
    <div class="container">
        <div class="top">
            <h1>Your Jobs</h1>
            <button class="btn" onclick="window.location.href='donejob.php'">Done Jobs</button>
        </div>
        <div class="card-container">
            <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $cardId = htmlspecialchars($row['id']);
                        $startAddress = htmlspecialchars($row['address']);
                        $endDestination = htmlspecialchars($row['destination']);
                        echo "<div class='card'>";
                        echo "<div class='card-content'>";
                        echo "<p><strong>Client Name:</strong> " . htmlspecialchars($row['name']) . "</p>";
                        echo "<p><strong>Team Number:</strong> " . htmlspecialchars($row['team_number']) . "</p>";
                        echo "<p><strong>Phone:</strong> " . htmlspecialchars($row['phone']) . "</p>";
                        echo "<p><strong>Address:</strong> " . $startAddress . "</p>";
                        echo "<p><strong>Destination:</strong> " . $endDestination . "</p>";
                        echo "<p><strong>Start Date:</strong> " . htmlspecialchars($row['st_date']) . "</p>";
                        echo "<p><strong>End Date:</strong> " . htmlspecialchars($row['end_date']) . "</p>";
                        echo "<p><strong>Remarks:</strong> " . htmlspecialchars($row['remakes']) . "</p>";
                        echo "<button class='btn btn-primary' data-id='$cardId' onclick='showModal(this)'>Done</button>";
                        echo "</div>";
                        echo "<div id='map-$cardId' class='map'></div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No available trips matching your city were found.</p>";
                }
            ?>
        </div>
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


        function initMap() {
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                const cardId = card.querySelector('button').getAttribute('data-id');
                const mapDiv = document.getElementById(`map-${cardId}`);
                const startAddress = card.querySelector('p:nth-child(4)').textContent.replace("Address: ", "").trim();
                const endDestination = card.querySelector('p:nth-child(5)').textContent.replace("Destination: ", "").trim();

                const geocoder = new google.maps.Geocoder();
                const directionsService = new google.maps.DirectionsService();
                const directionsRenderer = new google.maps.DirectionsRenderer();

                geocoder.geocode({ address: startAddress }, (startResults, status) => {
                    if (status === 'OK') {
                        geocoder.geocode({ address: endDestination }, (endResults, status) => {
                            if (status === 'OK') {
                                const map = new google.maps.Map(mapDiv, {
                                    center: startResults[0].geometry.location,
                                    zoom: 12
                                });
                                directionsRenderer.setMap(map);

                                const request = {
                                    origin: startResults[0].geometry.location,
                                    destination: endResults[0].geometry.location,
                                    travelMode: 'DRIVING'
                                };

                                directionsService.route(request, (result, status) => {
                                    if (status === 'OK') {
                                        directionsRenderer.setDirections(result);
                                    }
                                });
                            }
                        });
                    }
                });
            });
        }

        window.onload = initMap;
    </script>
</body>
</html>

<?php
    $stmt->close();
    $conn->close();
?>
