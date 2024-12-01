<?php
// view_framer_details.php

include('connect.php');

// Get the farmer ID from the URL
$id = $_GET['id'];

// Fetch farmer details from the database with the correct column name
$query = "SELECT * FROM framer_registration WHERE userid = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$farmer = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{
            text-align: center;
            background-color: white;
            border: green solid;
            padding: 30px 30px 100px 100px;
            margin: 100px 100px;
            color: green;
            font-size: 20px;
        }

        a {
    display: block; 
    padding: 12px 20px; 
    background-color: #2ba635; 
    color: #fff; 
    text-decoration: none; 
    text-align: center; 
    border-radius: 8px; 
    transition: background-color 0.3s ease; 
    margin-top: 20px;
}


a:hover {
    background-color: #248c34; 
}
    </style>
    <title>Framer Details</title>
</head>
<body>
    <h1>Framer Details for <?php echo $farmer['name']; ?></h1>
    <p><strong>ID:</strong> <?php echo $farmer['userid']; ?></p>
    <p><strong>Name:</strong> <?php echo $farmer['name']; ?></p>
    <p><strong>Email:</strong> <?php echo $farmer['email']; ?></p>
    <p><strong>Phone Number:</strong> <?php echo $farmer['phone_number']; ?></p>
    <p><strong>Business Name:</strong> <?php echo $farmer['business_name']; ?></p>
    <p><strong>Role Type:</strong> <?php echo $farmer['roteype']; ?></p>
    <a href="view_framer.php" class="btn">Back to list</a>
</body>
</html>

<?php
$stmt->close();
$con->close();
?>
