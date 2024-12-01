<?php 
include('../connect.php');

// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['uid'])){
    echo "<script> window.location.href='../login.php'; </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management</title>
    <style>
        /* Add your custom CSS here */
    </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="hotel-container">
    <h1>Manage Hotels</h1>
    <style>
      
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .blog-container {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #333;
        border-bottom: 2px solid #2d8f2d;
        padding-bottom: 10px;
        margin-bottom: 20px;
        font-size: 1.8em;
    }

    form {
        margin-bottom: 30px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input[type="text"],
    textarea,
    input[type="file"] {
        width: calc(100% - 22px);
        padding: 10px;
        margin: 5px 0 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 1em;
        transition: border-color 0.3s ease;
    }

    input[type="text"]:focus,
    textarea:focus {
        border-color: #2d8f2d;
    }

    input[type="submit"] {
        background-color: #2d8f2d;
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-radius: 5px;
        font-size: 1em;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
        background-color: #1b6b1b;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    table th,
    table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    table th {
        background-color: #2d8f2d;
        color: white;
        font-size: 1.1em;
    }

    table td img {
        max-width: 100px;
        border-radius: 5px;
    }

    table td form {
        display: inline;
    }

    table td input[type="submit"] {
        background-color: #d9534f;
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 5px;
        font-size: 0.9em;
        transition: background-color 0.3s ease;
    }

    table td input[type="submit"]:hover {
        background-color: #c9302c;
    }

    .no-records {
        text-align: center;
        padding: 20px;
        font-size: 1.2em;
        color: #777;
    }
</style>
    <!-- Form Section -->
    <form action="hotel.php" method="post" enctype="multipart/form-data">
        <?php if(isset($_GET['editid'])): 
            $editid = mysqli_real_escape_string($con, $_GET['editid']);
            $sql = "SELECT * FROM hotels WHERE hotelid = '$editid'";
            $res = mysqli_query($con, $sql);
            $editdata = mysqli_fetch_array($res);
        ?>
            <input type="hidden" name="hotelid" value="<?= htmlspecialchars($editdata['hotelid']) ?>">
        <?php endif; ?>

        <div>
            <input type="text" name="hotel_name" value="<?= isset($editdata) ? htmlspecialchars($editdata['hotel_name']) : '' ?>" placeholder="Enter Hotel Name" required>
        </div>
        <div>
            <input type="text" name="phone_number" value="<?= isset($editdata) ? htmlspecialchars($editdata['phone_number']) : '' ?>" placeholder="Enter Phone Number" required>
        </div>
        <div>
            <input type="text" name="location" value="<?= isset($editdata) ? htmlspecialchars($editdata['location']) : '' ?>" placeholder="Enter Location" required>
        </div>
        <div>
            <input type="email" name="email" value="<?= isset($editdata) ? htmlspecialchars($editdata['email']) : '' ?>" placeholder="Enter Email" required>
        </div>
        <div>
            <textarea name="description" placeholder="Enter Description" required><?= isset($editdata) ? htmlspecialchars($editdata['description']) : '' ?></textarea>
        </div>
        <div>
            <input type="number" name="order_amount" value="<?= isset($editdata) ? htmlspecialchars($editdata['order_amount']) : 0 ?>" placeholder="Enter Order Amount" min="0" required>
        </div>
        <div>
            <input type="submit" value="<?= isset($editdata) ? 'Update' : 'Add' ?>" name="<?= isset($editdata) ? 'update' : 'add' ?>">
        </div>
    </form>

    <!-- Table Section -->
    <div class="table-container">
        <table>
            <tr>
                <th>#</th>
                <th>Hotel Name</th>
                <th>Phone Number</th>
                <th>Location</th>
                <th>Email</th>
                <th>Description</th>
                <th>Order Amount</th>
                <th>Action</th>
            </tr>
            <?php
            $sql = "SELECT * FROM hotels";
            $res = mysqli_query($con, $sql);
            if(mysqli_num_rows($res) > 0){
                while($data = mysqli_fetch_array($res)){
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($data['hotelid']) ?></td>
                        <td><?= htmlspecialchars($data['hotel_name']) ?></td>
                        <td><?= htmlspecialchars($data['phone_number']) ?></td>
                        <td><?= htmlspecialchars($data['location']) ?></td>
                        <td><?= htmlspecialchars($data['email']) ?></td>
                        <td><?= htmlspecialchars($data['description']) ?></td>
                        <td><?= htmlspecialchars($data['order_amount']) ?></td>
                        <td class="action-links">
                            <a href="?editid=<?= htmlspecialchars($data['hotelid']) ?>">Edit</a>
                            <a class="delete" href="?deleteid=<?= htmlspecialchars($data['hotelid']) ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='8'>No hotels found.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>

<?php
// Handle adding a new hotel
if(isset($_POST['add'])){
    $hotel_name = mysqli_real_escape_string($con, $_POST['hotel_name']);
    $phone_number = mysqli_real_escape_string($con, $_POST['phone_number']);
    $location = mysqli_real_escape_string($con, $_POST['location']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $order_amount = mysqli_real_escape_string($con, $_POST['order_amount']);

    $sql = "INSERT INTO hotels (hotel_name, phone_number, location, email, description, order_amount) VALUES ('$hotel_name', '$phone_number', '$location', '$email', '$description', '$order_amount')";
    if(mysqli_query($con, $sql)){
        echo "<script>alert('Hotel added successfully'); window.location.href='hotel.php';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// Handle updating an existing hotel
if(isset($_POST['update'])){
    $hotelid = mysqli_real_escape_string($con, $_POST['hotelid']);
    $hotel_name = mysqli_real_escape_string($con, $_POST['hotel_name']);
    $phone_number = mysqli_real_escape_string($con, $_POST['phone_number']);
    $location = mysqli_real_escape_string($con, $_POST['location']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $order_amount = mysqli_real_escape_string($con, $_POST['order_amount']);

    $update_sql = "UPDATE hotels SET hotel_name='$hotel_name', phone_number='$phone_number', location='$location', email='$email', description='$description', order_amount='$order_amount' WHERE hotelid='$hotelid'";

    if(mysqli_query($con, $update_sql)){
        echo "<script>alert('Hotel updated successfully'); window.location.href='hotel.php';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// Handle deletion of a hotel
if(isset($_GET['deleteid'])){
    $deleteid = mysqli_real_escape_string($con, $_GET['deleteid']);
    $sql = "DELETE FROM hotels WHERE hotelid = '$deleteid'";
    if(mysqli_query($con, $sql)){
        echo "<script>alert('Hotel deleted successfully'); window.location.href='hotel.php';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
