<?php
    session_start();
    require '../../conn.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];

        $updateQuery = "UPDATE emergency SET status = 'Done' WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            http_response_code(500);
            echo "error";
        }

        $stmt->close();
        $conn->close();
    }
?>
