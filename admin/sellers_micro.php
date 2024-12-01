<?php 
include('../connect.php');

// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle approving a microgreen
if(isset($_GET['approveid'])){
    $approveid = mysqli_real_escape_string($con, $_GET['approveid']);
    $sql = "UPDATE farmer_micros SET approved = 1 WHERE microid = '$approveid'";
    mysqli_query($con, $sql);
    header("Location: sellers_micro.php");
}

// Handle deleting a microgreen
if(isset($_GET['deleteid'])){
    $deleteid = mysqli_real_escape_string($con, $_GET['deleteid']);
    $sql = "DELETE FROM farmer_micros WHERE microid = '$deleteid'";
    mysqli_query($con, $sql);
    header("Location: sellers_micro.php");
}

// Handle downloading microgreen data
if(isset($_GET['downloadid'])){
    $downloadid = mysqli_real_escape_string($con, $_GET['downloadid']);

    // Fetch microgreen data
    $sql = "SELECT fm.*, c.catname FROM farmer_micros fm 
            INNER JOIN categories c ON fm.catid = c.catid
            WHERE fm.microid = '$downloadid'";
    $res = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($res);

    if($data){
        // Create a directory for the microgreen inside admin/uploads/famers
        $folderPath = "../admin/uploads/famers/microgreen_{$data['microid']}";
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        // Copy image to the folder
        if($data['image']) {
            $imagePath = "../admin/uploads/images/" . $data['image'];
            copy($imagePath, $folderPath . "/" . basename($imagePath));
        }

        // Copy video to the folder
        if($data['video']) {
            $videoPath = "../admin/uploads/videos/" . $data['video'];
            copy($videoPath, $folderPath . "/" . basename($videoPath));
        }

        // Add details to a text file
        $details = "Title: {$data['title']}\n";
        $details .= "Description: {$data['description']}\n";
        $details .= "Phone: {$data['Phone']}\n";
        $details .= "Location: {$data['Location']}\n";
        $details .= "Price: {$data['price']}\n";
        $details .= "Quantity: {$data['quantity']}\n";
        $details .= "Discount: {$data['discount']}\n";
        $details .= "Available Stock: {$data['available_stock']}\n";
        $details .= "Category: {$data['catname']}\n";
        $details .= "Manufacturing Date: {$data['manufacturing_date']}\n";
        $details .= "Expiry Date: {$data['expiry_date']}\n";

        file_put_contents($folderPath . "/details.txt", $details);

        echo "<script>alert('Microgreen data downloaded successfully.'); window.location.href = 'sellers_micro.php';</script>";
    } else {
        echo "<script>alert('Failed to download microgreen data.'); window.location.href = 'sellers_micro.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Microgreens</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
    
}

header {
    background-color: transparent;
    color: green;
    padding: 20px;
    text-align: center;
    font-size: 1.8em;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
   
}

table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 20px auto;
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
    font-size: 0.68em;
}

th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #2d8f2d;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    font-weight: bold;
}

td {
    background-color: #ffffff;
}

tr:nth-child(even) td {
    background-color: #f9f9f9;
}

tr:hover td {
    background-color: #f1f1f1;
}

img {
    max-width: 100px;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

video {
    max-width: 150px;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

a {
    color: #007bff;
    text-decoration: none;
    font-size: 1em;
    margin-right: 10px;
    transition: color 0.3s ease, text-decoration 0.3s ease;
}

a:hover {
    color: #0056b3;
    text-decoration: underline;
}

a.delete {
    color: #dc3545;
    padding: 15px;
    margin-top: 38px;
}

a.download {
    color: #28a745;
    padding: 15px;
    margin-top: 38px;
}

.action-links {
    display: flex;
    gap: 10px;
}

.no-microgreens {
    text-align: center;
    padding: 20px;
    font-size: 1.2em;
    color: #666;
}

    </style>
</head>
<body>
<?php include('header.php'); ?>
<header>
    Admin - Manage Microgreens
</header>

<div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Description</th>
                <th>Phone</th>
                <th>Location</th>
                <th>Image</th>
                <th>Video</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Discount</th>
                <th>Available Stock</th>
                <th>Category</th>
                <th>Manufacturing Date</th>
                <th>Expiry Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT fm.*, c.catname FROM farmer_micros fm 
                    INNER JOIN categories c ON fm.catid = c.catid";
            $res = mysqli_query($con, $sql);
            if(mysqli_num_rows($res) > 0){
                while($data = mysqli_fetch_array($res)){
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($data['microid']) ?></td>
                        <td><?= htmlspecialchars($data['title']) ?></td>
                        <td><?= htmlspecialchars($data['description']) ?></td>
                        <td><?= htmlspecialchars($data['Phone']) ?></td>
                        <td><?= htmlspecialchars($data['Location']) ?></td>
                        <td>
                            <?php if($data['image']): ?>
                                <img src="../admin/uploads/images/<?= htmlspecialchars($data['image']) ?>" alt="Image"/>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($data['video']): ?>
                                <video controls>
                                    <source src="../admin/uploads/videos/<?= htmlspecialchars($data['video']) ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($data['price']) ?></td>
                        <td><?= htmlspecialchars($data['quantity']) ?></td>
                        <td><?= htmlspecialchars($data['discount']) ?></td>
                        <td><?= htmlspecialchars($data['available_stock']) ?></td>
                        <td><?= htmlspecialchars($data['catname']) ?></td>
                        <td><?= htmlspecialchars($data['manufacturing_date']) ?></td>
                        <td><?= htmlspecialchars($data['expiry_date']) ?></td>
                        <td><?= $data['approved'] ? 'Approved' : 'Pending' ?></td>
                        <td class="action-links">
                            <?php if(!$data['approved']): ?>
                                <a href="sellers_micro.php?approveid=<?= htmlspecialchars($data['microid']) ?>">Approve</a>
                            <?php endif; ?>
                            <a href="sellers_micro.php?deleteid=<?= htmlspecialchars($data['microid']) ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a>
                            <a href="sellers_micro.php?downloadid=<?= htmlspecialchars($data['microid']) ?>" class="download">Download</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo '<tr><td colspan="14" class="no-microgreens">No microgreens found</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
