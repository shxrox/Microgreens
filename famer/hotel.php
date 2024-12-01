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
        /* Container Styling */
.hotel-container {
    width: 90%;
    margin: 0 auto;
    text-align: center;
}

.hotel-container h1 {
    font-family: 'Lato', sans-serif; /* Ensure the font family is applied */
    font-weight: 500; /* Regular weight */
    font-size: 1rem; /* Adjust the size to your preference */
   
    letter-spacing: 0.1rem; /* Slight letter spacing for clarity */
   
    font-size: 2rem;
    margin-bottom: 20px;
    color: #0F2429;
}

/* Table Styling */
.table-container {
    overflow-x: auto;
    margin-top: 30px;
    margin-bottom: 60px;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Lato', sans-serif; /* Ensure the font family is applied */
    
   
    letter-spacing: 0.1rem; /* Slight letter spacing for clarity */
   
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

/* Table Header */
thead {
    background-color:#0F2429;
    color: white;
}

th, td {
    padding: 12px 15px;
    text-align: center;
    white-space: nowrap;
    font-size: 16px;
}

th {
    text-transform: uppercase;
    letter-spacing: 0.1rem;
}

td {
    border-bottom: 1px solid #ddd;
}

/* Striped Rows */
tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Hover Effect */
tbody tr:hover {
    background-color: #f1f1f1;
    transition: background-color 0.2s ease-in-out;
}

/* Mobile Responsiveness */
@media screen and (max-width: 768px) {
    table {
        font-size: 14px;
    }

    th, td {
        padding: 10px 12px;
    }
}

@media screen and (max-width: 480px) {
    table {
        font-size: 12px;
    }

    th, td {
        padding: 8px 10px;
    }

    .hotel-container h1 {
        font-size: 1.5rem;
    }
}

    </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="hotel-container">
    <h1>Manage Hotels</h1>

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

<?php include('footer.php'); ?>
<?php include('chat.php') ?>
<?php include('weather.php') ?>
<?php include('social.php'); ?>
</body>
</html>
