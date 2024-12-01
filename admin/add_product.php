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
    <title>Microgreens Management</title>
    <style>
    body {
    font-family: 'Arial', sans-serif;
    background-color: #f0f0f0;
    color: #333;
    margin: 0;
    padding: 0;
}

h1 {
    color: #2d8f2d;
    font-size: 2.5em;
    margin: 20px 0;
    text-align: center;
}

form {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 10px;
    padding: 20px;
}

form div {
    flex: 1 1 45%;
    min-width: 280px;
}

input[type="text"],
textarea,
input[type="number"],
select {
    width: 80%;
    padding: 10px;
    margin: 5px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 1em;
    background: #f8f9fa;
    color: #333;
}

input[type="file"] {
    padding: 12px;
    margin: 10px 0;
}

input[type="submit"] {
    background-color: #28a745;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1.1em;
    transition: background-color 0.3s ease, transform 0.2s ease;
    margin-top: 20px;
}

input[type="submit"]:hover {
    background-color: #218838;
    transform: scale(1.05);
}

.table-container {
    margin: 40px 20px;
}

table {
    width: calc(100% - 40px);
    margin: 0 auto;
    border-collapse: collapse;
    margin-bottom: 20px;
}

th, td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

th {
    background-color: #2d8f2d;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #e9f5e9;
}

.action-links a {
    color: #007bff;
    text-decoration: none;
    margin-right: 10px;
    font-size: 1.1em;
}

.action-links a:hover {
    text-decoration: underline;
}

.action-links a.delete {
    color: #dc3545;
}

img, video {
    border-radius: 8px;
    margin-top: 10px;
    max-width: 100%;
    height: auto;
    display: block;
}


    </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="product-container">
    <h1>Manage Microgreens</h1>

    <!-- Form Section -->
    <form action="add_product.php" method="post" enctype="multipart/form-data">
        <?php if(isset($_GET['editid'])): 
            $editid = mysqli_real_escape_string($con, $_GET['editid']);
            $sql = "SELECT * FROM micro WHERE microid = '$editid'";
            $res = mysqli_query($con, $sql);
            $editdata = mysqli_fetch_array($res);
        ?>
            <input type="hidden" name="microid" value="<?= htmlspecialchars($editdata['microid']) ?>">
        <?php endif; ?>

        <div>
            <input type="text" name="title" value="<?= isset($editdata) ? htmlspecialchars($editdata['title']) : '' ?>" placeholder="Enter title" required>
        </div>
        <div>
            <textarea name="description" placeholder="Enter description" required><?= isset($editdata) ? htmlspecialchars($editdata['description']) : '' ?></textarea>
        </div>
        <div>
            <input type="file" name="image" accept="image/*">
            <?php if(isset($editdata) && $editdata['image']): ?>
                <img src="./admin/uploads/images/<?= htmlspecialchars($editdata['image']) ?>" width="100" />
            <?php endif; ?>
        </div>
        <div>
            <input type="file" name="video" accept="video/*">
            <?php if(isset($editdata) && $editdata['video']): ?>
                <video width="320" height="240" controls>
                    <source src="./admin/uploads/videos/<?= htmlspecialchars($editdata['video']) ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php endif; ?>
        </div>
        <div>
            <input type="text" name="price" value="<?= isset($editdata) ? htmlspecialchars($editdata['price']) : '' ?>" placeholder="Enter price" required>
        </div>
        <div>
            <input type="number" name="quantity" value="<?= isset($editdata) ? htmlspecialchars($editdata['quantity']) : '' ?>" placeholder="Enter quantity" required>
        </div>
        <div>
            <input type="text" name="discount" value="<?= isset($editdata) ? htmlspecialchars($editdata['discount']) : '' ?>" placeholder="Enter discount (optional)">
        </div>
        <div>
            <input type="number" name="available_stock" value="<?= isset($editdata) ? htmlspecialchars($editdata['available_stock']) : '' ?>" placeholder="Enter available stock" required>
        </div>
        <div>
        <label for="manufacture_date">Manufacture Date:</label><input type="date" name="manufacture_date" value="<?= isset($editdata) ? htmlspecialchars($editdata['manufacture_date']) : '' ?>" placeholder="Manufacture Date" required>
        </div>
        <div>
        <label for="expire_date">Expiration Date:</label><input type="date" name="expire_date" value="<?= isset($editdata) ? htmlspecialchars($editdata['expire_date']) : '' ?>" placeholder="Expire Date" required>
        </div>
        <div>
        <label for="added_date">Added Date:</label><input type="date" name="added_date" value="<?= isset($editdata) ? htmlspecialchars($editdata['added_date']) : '' ?>" placeholder="Added Date" required>
        </div>
        <div>
            <select name="catid" required>
                <option value="">Select Category</option>
                <?php
                $sql = "SELECT * FROM categories";
                $res = mysqli_query($con, $sql);
                if(mysqli_num_rows($res) > 0){
                    while($data = mysqli_fetch_array($res)){
                        $selected = (isset($editdata) && $editdata['catid'] == $data['catid']) ? "selected" : "";
                        echo "<option value='{$data['catid']}' $selected>{$data['catname']}</option>";
                    }
                } else {
                    echo "<option value=''>No Category found</option>";
                }  
                ?> 
            </select>
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
                <th>Title</th>
                <th>Description</th>
                <th>Image</th>
                <th>Video</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Discount</th>
                <th>Available Stock</th>
                <th>Manufacture Date</th>
                <th>Expire Date</th>
                <th>Added Date</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
            <?php
            $sql = "SELECT micro.*, categories.catname FROM micro INNER JOIN categories ON micro.catid = categories.catid";
            $res = mysqli_query($con, $sql);
            if(mysqli_num_rows($res) > 0){
                while($data = mysqli_fetch_array($res)){
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($data['microid']) ?></td>
                        <td><?= htmlspecialchars($data['title']) ?></td>
                        <td><?= htmlspecialchars($data['description']) ?></td>
                        <td>
                            <?php if($data['image']): ?>
                                <img src="../admin/uploads/images/<?= htmlspecialchars($data['image']) ?>" width="100" />
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($data['video']): ?>
                                <video width="320" height="240" controls>
                                    <source src="../admin/uploads/videos/<?= htmlspecialchars($data['video']) ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($data['price']) ?></td>
                        <td><?= htmlspecialchars($data['quantity']) ?></td>
                        <td><?= htmlspecialchars($data['discount']) ?></td>
                        <td><?= htmlspecialchars($data['available_stock']) ?></td>
                        <td><?= htmlspecialchars($data['manufacture_date']) ?></td>
                        <td><?= htmlspecialchars($data['expire_date']) ?></td>
                        <td><?= htmlspecialchars($data['added_date']) ?></td>
                        <td><?= htmlspecialchars($data['catname']) ?></td>
                        <td class="action-links">
                            <a href="?editid=<?= htmlspecialchars($data['microid']) ?>">Edit</a>
                            <a class="delete" href="?deleteid=<?= htmlspecialchars($data['microid']) ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='11'>No microgreens found.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>


<?php
// Handle adding a new microgreen
if(isset($_POST['add'])){
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $quantity = mysqli_real_escape_string($con, $_POST['quantity']);
    $discount = mysqli_real_escape_string($con, $_POST['discount']);
    $available_stock = mysqli_real_escape_string($con, $_POST['available_stock']);
    $manufacture_date = mysqli_real_escape_string($con, $_POST['manufacture_date']);
    $expire_date = mysqli_real_escape_string($con, $_POST['expire_date']);
    $added_date = mysqli_real_escape_string($con, $_POST['added_date']);
    $catid = mysqli_real_escape_string($con, $_POST['catid']);
    
    // Handle image upload
    $image = "";
    if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ""){
        $image_target_dir = "../admin/uploads/images/";
        $image_target_file = $image_target_dir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($image_target_file, PATHINFO_EXTENSION));
        $imageUploadOk = 1;

        // Validate image
        if ($_FILES['image']['size'] > 5000000) $imageUploadOk = 0;
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) $imageUploadOk = 0;

        if ($imageUploadOk && move_uploaded_file($_FILES['image']['tmp_name'], $image_target_file)) {
            $image = $_FILES['image']['name'];
        } else {
            echo "Sorry, there was an error uploading your image file.";
            exit();
        }
    }

    // Handle video upload
    $video = "";
    if(isset($_FILES['video']['name']) && $_FILES['video']['name'] != ""){
        $video_target_dir = "../admin/uploads/videos/";
        $video_target_file = $video_target_dir . basename($_FILES['video']['name']);
        $videoFileType = strtolower(pathinfo($video_target_file, PATHINFO_EXTENSION));
        $videoUploadOk = 1;

        // Validate video
        if ($_FILES['video']['size'] > 50000000) $videoUploadOk = 0;
        if($videoFileType != "mp4") $videoUploadOk = 0;

        if ($videoUploadOk && move_uploaded_file($_FILES['video']['tmp_name'], $video_target_file)) {
            $video = $_FILES['video']['name'];
        } else {
            echo "Sorry, there was an error uploading your video file.";
            exit();
        }
    }

    $sql = "INSERT INTO micro (title, description, image, video, price, quantity, catid, discount, available_stock, manufacture_date, expire_date, added_date) VALUES ('$title', '$description', '$image', '$video', '$price', '$quantity', '$catid', '$discount', '$available_stock', '$manufacture_date', '$expire_date', '$added_date')";
    if(mysqli_query($con, $sql)){
        echo "<script>alert('Microgreen added successfully'); window.location.href='add_product.php';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// Handle updating an existing microgreen
if(isset($_POST['update'])){
    $microid = mysqli_real_escape_string($con, $_POST['microid']);
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $quantity = mysqli_real_escape_string($con, $_POST['quantity']);
    $discount = mysqli_real_escape_string($con, $_POST['discount']);
    $available_stock = mysqli_real_escape_string($con, $_POST['available_stock']);
    $manufacture_date = mysqli_real_escape_string($con, $_POST['manufacture_date']);
    $expire_date = mysqli_real_escape_string($con, $_POST['expire_date']);
    $added_date = mysqli_real_escape_string($con, $_POST['added_date']);
    $catid = mysqli_real_escape_string($con, $_POST['catid']);

    // Handle image upload
    $image = "";
    if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ""){
        $image_target_dir = "../admin/uploads/images/";
        $image_target_file = $image_target_dir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($image_target_file, PATHINFO_EXTENSION));
        $imageUploadOk = 1;

        // Validate image
        if ($_FILES['image']['size'] > 5000000) $imageUploadOk = 0;
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) $imageUploadOk = 0;

        if ($imageUploadOk && move_uploaded_file($_FILES['image']['tmp_name'], $image_target_file)) {
            $image = $_FILES['image']['name'];
        } else {
            echo "Sorry, there was an error uploading your image file.";
            exit();
        }
    }

    // Handle video upload
    $video = "";
    if(isset($_FILES['video']['name']) && $_FILES['video']['name'] != ""){
        $video_target_dir = "../admin/uploads/videos/";
        $video_target_file = $video_target_dir . basename($_FILES['video']['name']);
        $videoFileType = strtolower(pathinfo($video_target_file, PATHINFO_EXTENSION));
        $videoUploadOk = 1;

        // Validate video
        if ($_FILES['video']['size'] > 50000000) $videoUploadOk = 0;
        if($videoFileType != "mp4") $videoUploadOk = 0;

        if ($videoUploadOk && move_uploaded_file($_FILES['video']['tmp_name'], $video_target_file)) {
            $video = $_FILES['video']['name'];
        } else {
            echo "Sorry, there was an error uploading your video file.";
            exit();
        }
    }

    $update_sql = "UPDATE micro SET title='$title', description='$description', price='$price', quantity='$quantity', catid='$catid', discount='$discount', available_stock='$available_stock',manufacture_date='$manufacture_date',expire_date='$expire_date',added_date='$added_date' ";

    if ($image != "") {
        $update_sql .= ", image='$image'";
    }
    if ($video != "") {
        $update_sql .= ", video='$video'";
    }
    $update_sql .= " WHERE microid='$microid'";

    if(mysqli_query($con, $update_sql)){
        echo "<script>alert('Microgreen updated successfully'); window.location.href='add_product.php';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}


// Handle deletion of a microgreen
if(isset($_GET['deleteid'])){
    $microid = $_GET['deleteid'];
    $sql = "DELETE FROM micro WHERE microid = '$microid'";
    if(mysqli_query($con, $sql)){
        echo "<script>alert('Microgreen deleted successfully'); window.location.href='add_product.php';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
