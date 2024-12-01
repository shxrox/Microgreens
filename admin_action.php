<?php
// Include database connection
include './db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $conn->real_escape_string($_POST['id']);
    $action = $_POST['action'];

    if ($action === "Approve") {
        // Fetch request data
        $stmt = $conn->prepare("SELECT full_name, email, phone_number, business_name, password FROM vendor_request WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data) {
            // Insert into vendor table
            $stmt = $conn->prepare("INSERT INTO vendor (full_name, email, phone_number, business_name, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $data['full_name'], $data['email'], $data['phone_number'], $data['business_name'], $data['password']);
            if ($stmt->execute()) {
                // Delete from vendor_request table
                $stmt = $conn->prepare("DELETE FROM vendor_request WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                echo "<script>alert('Vendor request approved and moved to vendors.'); window.location.href = './vendor_requests.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = './vendor_requests.php';</script>";
            }
        }
        $stmt->close();
    } elseif ($action === "Delete") {
        // Delete from vendor_request table
        $stmt = $conn->prepare("DELETE FROM vendor_request WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "<script>alert('Vendor request deleted.'); window.location.href = './vendor_request.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = './vendor_request.php';</script>";
        }
        $stmt->close();
    }

    $conn->close();
}
?>
