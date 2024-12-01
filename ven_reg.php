<?php
    // Include database connection
    include './db_connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Basic form validation
        if ($_POST['password'] !== $_POST['confirm_password']) {
            echo "<script>alert('Passwords do not match!'); window.location.href = './vendor_registration.php';</script>";
            exit;
        }

        $full_name = $conn->real_escape_string($_POST['f_name']);
        $email = $conn->real_escape_string($_POST['email']);
        $phone_number = $conn->real_escape_string($_POST['phone_number']);
        $business_name = $conn->real_escape_string($_POST['b_name']);
        $password = $conn->real_escape_string($_POST['password']);

        // Check if email already exists
        $email_check_stmt = $conn->prepare("SELECT id FROM vendor_request WHERE email = ?");
        $email_check_stmt->bind_param("s", $email);
        $email_check_stmt->execute();
        $email_check_stmt->store_result();

        if ($email_check_stmt->num_rows > 0) {
            // Email already exists
            echo "<script>alert('Email address already in use!'); window.location.href = './vendor_registration.php';</script>";
            $email_check_stmt->close();
            $conn->close();
            exit;
        }

        // Check if phone number already exists
        $phone_check_stmt = $conn->prepare("SELECT id FROM vendor_request WHERE phone_number = ?");
        $phone_check_stmt->bind_param("s", $phone_number);
        $phone_check_stmt->execute();
        $phone_check_stmt->store_result();

        if ($phone_check_stmt->num_rows > 0) {
            // Phone number already exists
            echo "<script>alert('Phone number already in use!'); window.location.href = './vendor_registration.php';</script>";
            $phone_check_stmt->close();
            $email_check_stmt->close();
            $conn->close();
            exit;
        }

        // Both email and phone number are unique, proceed with the insertion
        $stmt = $conn->prepare("INSERT INTO vendor_request (full_name, email, phone_number, business_name, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $full_name, $email, $phone_number, $business_name, $password);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!'); window.location.href = './vendor_registration.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = './vendor_registration.php';</script>";
        }

        $stmt->close();
        $phone_check_stmt->close();
        $conn->close();
    }
?>
