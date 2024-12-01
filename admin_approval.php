<?php
    include('connect.php');

    // Fetch pending vendors
    $sql = "SELECT * FROM pending_vendors WHERE status='pending'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row['id'] . " - Name: " . $row['full_name'] . " - Email: " . $row['email'] . "<br>";
            // Provide an interface for admin to approve or reject
            echo "<a href='approve_vendor.php?id=" . $row['id'] . "'>Approve</a> | <a href='reject_vendor.php?id=" . $row['id'] . "'>Reject</a><br><br>";
        }
    } else {
        echo "No pending vendors.";
    }

    $conn->close();
?>
