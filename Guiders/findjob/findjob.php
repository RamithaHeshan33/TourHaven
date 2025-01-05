<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("location: ../login.php");
    exit();
}

require '../../conn.php';
require '../nav.php';
$guider_email = $_SESSION['email'];

// Fetching guider's data
$cityQuery = "SELECT city FROM guiders WHERE email = ?";
$stmt = $conn->prepare($cityQuery);
$stmt->bind_param("s", $guider_email);
$stmt->execute();
$cityResult = $stmt->get_result();
$guiderCity = $cityResult->fetch_assoc()['city'];
$stmt->close();

// Fetching trip details
$sql = "SELECT * FROM trip_details WHERE guider_mail IS NULL AND address LIKE ?";
$stmt = $conn->prepare($sql);
$searchKeyword = "%" . $guiderCity . "%";
$stmt->bind_param("s", $searchKeyword);
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
    <link rel="stylesheet" href="findjob.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4WKu5raw64v4-CB8bYSq7SMtFikfu5lg"></script>
</head>
<body>
    <div class="body">
        <h2>Trip Details</h2>
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
                        echo "<p><strong>Created At:</strong> " . htmlspecialchars($row['created_at']) . "</p>";
                        echo "<button class='btn btn-primary' data-id='$cardId' onclick='showModal(this)'>Take This Job</button>";
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
    


    <!-- Modal Structure -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>Are you sure you want to take this job?</p>
            <button id="confirmButton" class="btn btn-success">Yes</button>
            <button onclick="closeModal()" class="btn btn-danger">No</button>
        </div>
    </div>

    <script>
        let selectedJobId = null;

        function showModal(button) {
            selectedJobId = button.getAttribute('data-id');
            document.getElementById('confirmationModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('confirmationModal').style.display = 'none';
        }

        document.getElementById('confirmButton').addEventListener('click', function () {
            if (selectedJobId) {
                fetch('take_job.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ jobId: selectedJobId })
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        alert('Job successfully taken!');
                        window.location.href = 'tookjobs.php';
                    } else {
                        alert('Failed to take the job. Please try again.');
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
                closeModal();
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

    <style>
        
    </style>
</body>
</html>
