<?php
// delete_framer.php

include('connect.php');

// Get the farmer ID from the URL
$id = $_GET['id'];

// Prepare the delete query with the correct column name
$query = "DELETE FROM framer_registration WHERE userid = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>alert('Farmer record deleted successfully.'); window.location.href='view_framer.php';</script>";
} else {
    echo "<script>alert('Failed to delete farmer record.'); window.location.href='view_framer.php';</script>";
}

$stmt->close();
$con->close();
?>
