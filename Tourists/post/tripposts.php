<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("location: ../login.php");
    exit();
}
require '../nav.php';
require('../../conn.php');

$tourist_email = $_SESSION['email'];

$trippost = "SELECT td.*, g.name AS gname, g.phone AS gphone FROM trip_details td 
             LEFT JOIN guiders g ON td.guider_mail = g.email 
             WHERE status ='Pending' AND tourist_mail = ?";
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
    <link rel="stylesheet" href="trippost.css">
        
</head>
<body>
    <div class="container">
        <div class="top">
            <h1>Your Trip Schedules</h1>
            <button class="btn" onclick="window.location.href='donetrips.php'">Done</button>
            <!-- <button class="btn" onclick="window.location.href='../findcar/findcar.php'">Back</button> -->
        </div>

        <div class="card-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='card'>";
                    echo "<p><strong>Guider Name:</strong> <span data-guiderName>" . htmlspecialchars($row['gname']) . "</p>";
                    echo "<p><strong>Guider Contact:</strong> <span data-guiderConact>" . htmlspecialchars($row['gphone']) . "</p>";
                    echo "<p><strong>Guider Email:</strong> <span data-guiderEmail>" . htmlspecialchars($row['guider_mail']) . "</p>";
                    echo "<p><strong>Trip Status: </strong>" . htmlspecialchars($row['status']) . "</p>";

                    echo "<hr class='line'>";

                    echo "<p><strong>Name:</strong> <span data-name>" . htmlspecialchars($row['name']) . "</span></p>";
                    echo "<p><strong>Name:</strong> <span data-touristEmail>" . htmlspecialchars($row['tourist_mail']) . "</span></p>";
                    echo "<p><strong>Team Number:</strong> <span data-team-number>" . htmlspecialchars($row['team_number']) . "</span></p>";
                    echo "<p><strong>Phone:</strong> <span data-phone>" . htmlspecialchars($row['phone']) . "</span></p>";
                    echo "<p><strong>Address:</strong> <span data-address>" . htmlspecialchars($row['address']) . "</span></p>";
                    echo "<p><strong>Destination:</strong> <span data-destination>" . htmlspecialchars($row['destination']) . "</span></p>";
                    echo "<p><strong>Start Date:</strong> <span data-st-date>" . htmlspecialchars($row['st_date']) . "</span></p>";
                    echo "<p><strong>End Date:</strong> <span data-end-date>" . htmlspecialchars($row['end_date']) . "</span></p>";
                    echo "<p><strong>Remarks:</strong> <span data-remarks>" . htmlspecialchars($row['remakes']) . "</span></p>";

                    echo "<div class='btns'>";
                    echo "<button class='btn btn-update' data-id='" . $row['id'] . "' onclick='showUpdateModal(this)'>Update</button>";
                    echo "<button class='btn btn-delete' data-id='" . $row['id'] . "' onclick='showDeleteModal(this)'>Delete</button>";
                    echo "<button class='btn btn-update' data-id='" . $row['id'] . "' onclick='showRateModal(this)'>Rate</button>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>There are no Trip Schedules that you are scheduled.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Update Modal -->
    <div id="update-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('update-modal')">&times;</span>
            <h2>Update Trip Details</h2>
            <form id="update-form" onsubmit="updateTrip(event)">
                <input type="hidden" id="update-id" name="id">
                <label for="update-name">Trip Name:</label>
                <input type="text" id="update-name" name="name" required>
                <label for="update-team-number">Team Number:</label>
                <input type="text" id="update-team-number" name="team_number" required>
                <label for="update-phone">Phone:</label>
                <input type="text" id="update-phone" name="phone" required>
                <label for="update-address">Address:</label>
                <input type="text" id="update-address" name="address" required>
                <label for="update-destination">Destination:</label>
                <input type="text" id="update-destination" name="destination" required>
                <label for="update-st-date">Start Date:</label>
                <input type="date" id="update-st-date" name="st_date" required>
                <label for="update-end-date">End Date:</label>
                <input type="date" id="update-end-date" name="end_date" required>
                <label for="update-remarks">Remarks:</label>
                <textarea id="update-remarks" name="remarks" style="width: 100%; padding: 10px;" rows="4"></textarea>
                <div style="display: flex; gap:20px">
                    <button type="submit" class="btn-update">Update</button>
                    <button type="button" class="btn-delete" onclick="closeModal('update-modal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('delete-modal')">&times;</span>
            <input type="hidden" id="update-id" name="id">
            <h2>Confirm Deletion</h2>
            <p>Are you sure you want to delete this trip?</p>
            <input type="hidden" id="delete-id">
            <div style="display: flex; gap: 20px; margin-top: 20px">
                <button class="btn-delete" onclick="deleteTrip()">Yes</button>
                <button class="btn-update" onclick="closeModal('delete-modal')">No</button>
            </div>
        </div>
    </div>

    <!-- Rate Modal -->
    <div id="rate-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('rate-modal')">&times;</span>
            <h2>Rate for the Companion</h2>
            <form id="update-form" onsubmit="rateCompanion(event)">
                <input type="hidden" id="guider-id" name="id">
                <input type="hidden" id="tourist-email" name="tourist-email">
                <label for="guider-name">Trip Name:</label>
                <input type="text" id="guider-name" name="name" readonly required>
                <label for="guider-email">Email:</label>
                <input type="text" id="guider-email" name="email" readonly>
                
                <!-- Star Rating Section -->
                <label for="rating-stars">Rate:</label>
                <div id="rating-stars" style="display: flex; gap: 5px; font-size: 24px; cursor: pointer;">
                    <span class="star" data-value="1">&#9734;</span>
                    <span class="star" data-value="2">&#9734;</span>
                    <span class="star" data-value="3">&#9734;</span>
                    <span class="star" data-value="4">&#9734;</span>
                    <span class="star" data-value="5">&#9734;</span>
                </div>
                <input type="hidden" id="star-rating" name="rating" required>
                <br>

                <label for="guider-rate">Comments:</label>
                <textarea id="guider-rate" name="guider-rate" placeholder="Enter your feedback" style="width: 100%; padding: 10px;" rows="4"></textarea>
                <div style="display: flex; gap: 20px;">
                    <button type="submit" class="btn-update">Submit</button>
                    <button type="button" class="btn-delete" onclick="closeModal('rate-modal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

</body>

<script>
    function showUpdateModal(button) {
        const card = button.closest('.card');
        const id = button.getAttribute('data-id');

        // Pre-fill modal form with details
        document.getElementById('update-id').value = id;
        document.getElementById('update-name').value = card.querySelector('[data-name]').textContent.trim();
        document.getElementById('update-team-number').value = card.querySelector('[data-team-number]').textContent.trim();
        document.getElementById('update-phone').value = card.querySelector('[data-phone]').textContent.trim();
        document.getElementById('update-address').value = card.querySelector('[data-address]').textContent.trim();
        document.getElementById('update-destination').value = card.querySelector('[data-destination]').textContent.trim();
        document.getElementById('update-st-date').value = card.querySelector('[data-st-date]').textContent.trim();
        document.getElementById('update-end-date').value = card.querySelector('[data-end-date]').textContent.trim();
        document.getElementById('update-remarks').value = card.querySelector('[data-remarks]').textContent.trim();

        document.getElementById('update-modal').style.display = 'block';
    }

    function showRateModal(button) {
        const card = button.closest('.card');
        const id = button.getAttribute('data-id');

        // Pre-fill modal form with details
        document.getElementById('guider-id').value = id;
        document.getElementById('tourist-email').value =card.querySelector('[data-touristEmail]').textContent.trim();
        document.getElementById('guider-name').value = card.querySelector('[data-guiderName]').textContent.trim();
        document.getElementById('guider-email').value = card.querySelector('[data-guiderEmail]').textContent.trim();
        
        document.getElementById('rate-modal').style.display = 'block';
    }

    function showDeleteModal(button) {
        const id = button.getAttribute('data-id');
        document.getElementById('delete-id').value = id;
        document.getElementById('delete-modal').style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    function updateTrip(event) {
        event.preventDefault();
        const formData = new FormData(document.getElementById('update-form'));

        fetch('update_trip.php', {
            method: 'POST',
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                alert(data.message);
                if (data.status === 'success') {
                    location.reload(); // Reload the page to show updated data
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }

    function deleteTrip() {
        const id = document.getElementById('delete-id').value;

        fetch('delete_trip.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${encodeURIComponent(id)}`,
        })
            .then((response) => response.json())
            .then((data) => {
                alert(data.message);
                if (data.status === 'success') {
                    location.reload(); // Reload the page to reflect deletion
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }

    function rateCompanion(event) {
        event.preventDefault(); // Prevent form submission

        // Collect form data
        const formData = {
            guider_id: document.getElementById('guider-id').value,
            tourist_email: document.getElementById('tourist-email').value,
            guider_name: document.getElementById('guider-name').value,
            guider_email: document.getElementById('guider-email').value,
            rating: document.getElementById('star-rating').value,
            comments: document.getElementById('guider-rate').value,
        };

        // Send data to the server via an AJAX request
        fetch('submit-rating.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData),
        })
            .then((response) => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Failed to submit rating');
            })
            .then((data) => {
                alert('Rating submitted successfully!');
                closeModal('rate-modal');
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('An error occurred while submitting the rating.');
            });
    }


    // JavaScript for Star Rating
    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', function() {
            let value = this.getAttribute('data-value');
            document.getElementById('star-rating').value = value;
            highlightStars(value);
        });
    });

    function highlightStars(rating) {
        document.querySelectorAll('.star').forEach(star => {
            if (star.getAttribute('data-value') <= rating) {
                star.innerHTML = '&#9733;'; // Filled star
            } else {
                star.innerHTML = '&#9734;'; // Empty star
            }
        });
    }


</script>

</html>
