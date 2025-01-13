<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("location: ../login.php");
    exit();
}

require '../nav.php';
require '../../conn.php';

// Fetch vehicles for the logged-in guider
$email = $_SESSION['email'];
$sql = "SELECT * FROM vehicles WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$vehicles = $result->fetch_all(MYSQLI_ASSOC);

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="vehicle.css">
    <title>Manage Vehicles</title>
</head>
<body>
    <div class="body">
        <?php if (!empty($vehicles)): ?>
            <div class="vehicle-card" id="vehicle-card">
                <h1>Your Vehicles</h1>

                <?php if ($message == 'update'): ?>
                    <div class="success" id="success-alert">Your vehicle updated successfully!</div>
                <?php elseif ($message == 'Delete'): ?>
                    <div class="success" id="success-alert">Your vehicle deleted successfully!</div>
                <?php endif; ?>

                <img id="vehicle-image" src="<?php echo htmlspecialchars($vehicles[0]['main_image']); ?>" alt="Vehicle Image">
                <h3 id="vehicle-name"><?php echo htmlspecialchars($vehicles[0]['vehicle_name']); ?></h3>
                <!-- <p id="vehicle-type">Type: <?php echo htmlspecialchars($vehicles[0]['vehicle_type']); ?></p> -->
                <p id="vehicle-description"><?php echo htmlspecialchars($vehicles[0]['description']); ?></p>

                <div class="vehicle-actions">
                    <button id="update-btn">Update</button>
                    <button id="delete-btn">Delete</button>
                </div>

                <div class="navigation-buttons">
                    <button id="prev-btn" disabled>Previous</button>
                    <button id="next-btn" <?php echo count($vehicles) <= 1 ? 'disabled' : ''; ?>>Next</button>
                </div>

                <h3 id="price" class="price-val">Per KM - Rs. <?php echo htmlspecialchars($vehicles[0]['price']); ?></h3>
            </div>
        <?php else: ?>
            <!-- No Vehicles -->
            <div class="vehicle-image">
                <h1 class="empty-title">No Vehicles</h1>
                <img src="../../res/Search-bro.png" alt="search image" class="search-image">
            </div>
        <?php endif; ?>

        <div class="vehicle-form">
            <!-- Vehicle Addition Form -->
            <form action="add-vehicle.php" method="post" enctype="multipart/form-data">
                <h1 class="form-title">Add Vehicle Details</h1>
                <?php if ($message == 'success'): ?>
                    <div class="success" id="success-alert">Your vehicle added successfully!</div>
                <?php endif; ?>

                <div class="form">
                    <input type="hidden" name="email" value="<?php echo $_SESSION['email']; ?>" readonly>

                    <label for="vehicle_name">Vehicle Name</label>
                    <input type="text" name="vehicle_name" id="vehicle_name" placeholder="Enter Vehicle Name" required>

                    <label for="price">Price per KM(kilo meter) LKR</label>
                    <input type="number" name="price" id="price" placeholder="Enter Price per KM" required>

                    <label for="vehicle_type">Vehicle Type</label>
                    <select name="vehicle_type" id="vehicle_type" required>
                        <option value="" disabled selected>Select Vehicle Type</option>
                        <option value="Car">Car</option>
                        <option value="Van">Van</option>
                        <option value="Bus">Bus</option>
                        <option value="Wheeler">Wheeler</option>
                        <option value="AC_Bus">A/C Bus</option>
                    </select>

                    <label for="description">Vehicle Description</label>
                    <textarea name="description" id="description" placeholder="Enter vehicle details (e.g., capacity, features)" rows="4" required></textarea>

                    <label for="main-image">Vehicle Image</label>
                    <input type="file" name="main-image" id="main-image" accept="image/*" required>

                    <input type="submit" value="Submit">
                </div>
            </form>
        </div>
    </div>

    <!-- Update Modal -->
    <div id="update-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="close-update-modal">&times;</span>
            <h2>Update Vehicle</h2>
            <form action="update-vehicle.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="vehicle_id" id="update-vehicle-id">

                <label for="update-main-image">Primary Image</label>
                <img id="current-main-image" src="" alt="Current Main Image" style="width: 150px; height: auto;">
                <input type="file" name="main_image" id="update-main-image" accept="image/*">

                <label for="update-vehicle-name">Vehicle Name</label>
                <input type="text" name="vehicle_name" id="update-vehicle-name" required>

                <label for="update-vehicle-type">Vehicle Type</label>
                <select name="vehicle_type" id="update-vehicle-type" required>
                    <option value="Car">Car</option>
                    <option value="Van">Van</option>
                    <option value="Bus">Bus</option>
                    <option value="AC_Bus">A/C Bus</option>
                </select>

                <label for="update-description">Description</label>
                <textarea name="description" id="update-description" rows="4" required></textarea>

                <label for="update-price">Price per KM (LKR)</label>
                <input type="number" name="price" id="update-price" step="0.01" min="0" required>

                <input type="submit" value="Update Vehicle">
            </form>
        </div>
    </div>


    <!-- Delete Modal -->
    <div id="delete-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="close-delete-modal">&times;</span>
            <h2>Delete Vehicle</h2>
            <p>Are you sure you want to delete this vehicle?</p>
            <form action="delete-vehicle.php" method="post">
                <input type="hidden" name="vehicle_id" id="delete-vehicle-id">
                <button type="submit" class="delete-btn">Delete</button>
                <button type="button" id="cancel-delete">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        const vehicles = <?php echo json_encode($vehicles); ?>;
        let currentIndex = 0;

        const vehicleImage = document.getElementById('vehicle-image');
        const vehicleName = document.getElementById('vehicle-name');
        const vehicleDescription = document.getElementById('vehicle-description');

        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');

        const updateModal = document.getElementById('update-modal');
        const deleteModal = document.getElementById('delete-modal');

        const updateBtn = document.getElementById('update-btn');
        const deleteBtn = document.getElementById('delete-btn');

        const closeUpdateModal = document.getElementById('close-update-modal');
        const closeDeleteModal = document.getElementById('close-delete-modal');

        const updateVehicleId = document.getElementById('update-vehicle-id');
        const updateVehicleName = document.getElementById('update-vehicle-name');
        const updateVehicleType = document.getElementById('update-vehicle-type');
        const updateDescription = document.getElementById('update-description');
        const updatePrice = document.getElementById('update-price');

        const deleteVehicleId = document.getElementById('delete-vehicle-id');

        const cancelDelete = document.getElementById('cancel-delete');

        // Update Vehicle Details
        function updateVehicleDetails(index) {
            const vehicle = vehicles[index];
            vehicleImage.src = vehicle.main_image;
            vehicleName.textContent = vehicle.vehicle_name;
            vehicleDescription.textContent = vehicle.description;
            document.getElementById('price').textContent = `Per KM - Rs. ${parseFloat(vehicle.price).toFixed(2)}`;

            prevBtn.disabled = index === 0;
            nextBtn.disabled = index === vehicles.length - 1;
        }

        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateVehicleDetails(currentIndex);
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentIndex < vehicles.length - 1) {
                currentIndex++;
                updateVehicleDetails(currentIndex);
            }
        });


        updateBtn.addEventListener('click', () => {
            const vehicle = vehicles[currentIndex];

            updateVehicleId.value = vehicle.id;
            updateVehicleName.value = vehicle.vehicle_name;
            updateVehicleType.value = vehicle.vehicle_type;
            updateDescription.value = vehicle.description;
            updatePrice.value = vehicle.price;

            document.getElementById('current-main-image').src = vehicle.main_image;
            updateModal.style.display = 'block';
        });


        closeUpdateModal.addEventListener('click', () => {
            updateModal.style.display = 'none';
        });

        deleteBtn.addEventListener('click', () => {
            const vehicle = vehicles[currentIndex];
            deleteVehicleId.value = vehicle.id;
            deleteModal.style.display = 'block';
        });

        closeDeleteModal.addEventListener('click', () => {
            deleteModal.style.display = 'none';
        });

        cancelDelete.addEventListener('click', () => {
            deleteModal.style.display = 'none';
        });

    </script>
</body>

<script>
    // hide the alert after 10 seconds
    setTimeout(function() {
        var alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 10000);
</script>

</html>
