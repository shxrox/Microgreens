<?php
// view_framer.php

include('connect.php');

// Fetch all farmers from the database
$query = "SELECT * FROM framer_registration";
$result = $con->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Framer Details</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px white;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color:  #4CAF50;
            color: white;
        }
        td a {
            color: #32CD32;
            text-decoration: none;
        }
        td a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php include('header.php')  ?>
    <h1>Framer Details</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Business Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['userid']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone_number']; ?></td>
                    <td><?php echo $row['business_name']; ?></td>
                    <td>
                        <a href="view_framer_details.php?id=<?php echo $row['userid']; ?>">View</a> | 
                        <a href="delete_framer.php?id=<?php echo $row['userid']; ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$con->close();
?>
