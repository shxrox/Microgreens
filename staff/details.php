<?php 
include('../connect.php');

// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the user is logged in
if(!isset($_SESSION['uid'])){
    echo "<script> window.location.href='../login.php';  </script>";
    exit();
}

// Check if an edit action is requested
$editid = isset($_GET['editid']) ? $_GET['editid'] : null;
if($editid) {
    $edit_sql = "SELECT * FROM micro WHERE microid = '$editid'";
    $edit_res = mysqli_query($con, $edit_sql);
    $edit_data = mysqli_fetch_array($edit_res);

    // Check if microgreen data was found
    if(!$edit_data) {
        echo "<script>alert('Microgreen not found.'); window.location.href='details.php';</script>";
        exit();
    }
}

// Handle the form submission for adding or updating microgreens
if(isset($_POST['add']) || isset($_POST['update'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $discount = $_POST['discount'];
    $available_stock = $_POST['available_stock'];
    $manufacture_date = mysqli_real_escape_string($con, $_POST['manufacture_date']);
    $expire_date = mysqli_real_escape_string($con, $_POST['expire_date']);
    $added_date = mysqli_real_escape_string($con, $_POST['added_date']);
    $catid = $_POST['catid'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $video = $_FILES['video']['name'];
    $video_tmp = $_FILES['video']['tmp_name'];

    if($image) {
        move_uploaded_file($image_tmp, "../admin/uploads/images/$image");
    } else if (isset($edit_data['image'])) {
        $image = $edit_data['image'];
    }

    if($video) {
        move_uploaded_file($video_tmp, "../admin/uploads/videos/$video");
    } else if (isset($edit_data['video'])) {
        $video = $edit_data['video'];
    }

    if(isset($_POST['add'])) {
        $insert_sql = "INSERT INTO micro (title, description, image, video, price, quantity, discount, available_stock, manufacture_date, expire_date, added_date, catid) VALUES ('$title', '$description', '$image', '$video', '$price', '$quantity', '$discount', '$available_stock','$manufacture_date', '$expire_date', '$added_date', '$catid')";
        if(mysqli_query($con, $insert_sql)) {
            echo "<script>alert('Microgreen added successfully.'); window.location.href='details.php';</script>";
        } else {
            echo "<script>alert('Failed to add microgreen.');</script>";
        }
    }

    if(isset($_POST['update']) && $editid) {
        $update_sql = "UPDATE micro SET title='$title', description='$description', image='$image', video='$video', price='$price', quantity='$quantity', discount='$discount', available_stock='$available_stock', catid='$catid' WHERE microid='$editid'";
        if(mysqli_query($con, $update_sql)) {
            echo "<script>alert('Microgreen updated successfully.'); window.location.href='details.php';</script>";
        } else {
            echo "<script>alert('Failed to update microgreen.');</script>";
        }
    }
}

// Handle the delete action
if(isset($_GET['deleteid'])) {
    $deleteid = $_GET['deleteid'];
    $delete_sql = "DELETE FROM micro WHERE microid = '$deleteid'";
    if(mysqli_query($con, $delete_sql)) {
        echo "<script>alert('Microgreen deleted successfully.'); window.location.href='details.php';</script>";
    } else {
        echo "<script>alert('Failed to delete microgreen.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../admin/movies.css">
    <title>Admin - Manage Microgreens</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }



        .details-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 2em;
            border-bottom: 2px solid #2d8f2d;
            padding-bottom: 10px;
        }

        form {
            margin-bottom: 30px;
        }

        form input[type="text"],
        form input[type="number"],
        form input[type="file"],
        form textarea,
        form select,
        form input[type="submit"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        form input[type="submit"] {
            background-color: #2d8f2d;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #1b6b1b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
        }

        table th {
            background-color: #2d8f2d;
            color: white;
            font-size: 1.1em;
        }

        table td {
            background-color: white;
            border-bottom: 1px solid #ddd;
        }

        table img,
        table video {
            max-width: 100px;
            border-radius: 5px;
        }

        table a {
            text-decoration: none;
            color: #2d8f2d;
            margin-right: 10px;
        }

        table a:hover {
            text-decoration: underline;
        }

        .no-records {
            text-align: center;
            padding: 20px;
            font-size: 1.2em;
            color: #777;
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="details-container">
    <!-- Section for Microgreen Management -->
    <h2><?php echo $editid ? 'Edit' : 'Add'; ?> Microgreen</h2>
    <form action="details.php<?php echo $editid ? '?editid='.$editid : ''; ?>" method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Enter Microgreen Title" value="<?php echo isset($edit_data) ? $edit_data['title'] : ''; ?>" required>
        <textarea name="description" placeholder="Enter Description" required><?php echo isset($edit_data) ? $edit_data['description'] : ''; ?></textarea>
        <input type="file" name="image" accept="image/*">
        <input type="file" name="video" accept="video/*">
        <input type="text" name="price" placeholder="Enter Price" value="<?php echo isset($edit_data) ? $edit_data['price'] : ''; ?>" required>
        <input type="number" name="quantity" placeholder="Enter Quantity" value="<?php echo isset($edit_data) ? $edit_data['quantity'] : ''; ?>" required>
        <input type="text" name="discount" placeholder="Enter Discount (Optional)" value="<?php echo isset($edit_data) ? $edit_data['discount'] : ''; ?>">
        <input type="number" name="available_stock" placeholder="Enter Available Stock" value="<?php echo isset($edit_data) ? $edit_data['available_stock'] : ''; ?>" required>
        <div>
        <label for="manufacture_date">Manufacture Date:</label><input type="date" name="manufacture_date" value="<?= isset($edit_data) ? htmlspecialchars($edit_data['manufacture_date']) : '' ?>" placeholder="Manufacture Date" required>
        </div>
        <div>
        <label for="expire_date">Expiration Date:</label><input type="date" name="expire_date" value="<?= isset($edit_data) ? htmlspecialchars($edit_data['expire_date']) : '' ?>" placeholder="Expire Date" required>
        </div>
        <div>
        <label for="added_date">Added Date:</label><input type="date" name="added_date" value="<?= isset($edit_data) ? htmlspecialchars($edit_data['added_date']) : '' ?>" placeholder="Added Date" required>
        </div>
        <select name="catid" required>
            <option value="">Select Category</option>
            <?php
            $sql = "SELECT * FROM categories";
            $res = mysqli_query($con, $sql);
            if(mysqli_num_rows($res) > 0){
                while($data = mysqli_fetch_array($res)){
                    $selected = isset($edit_data) && $edit_data['catid'] == $data['catid'] ? 'selected' : '';
                    echo "<option value='{$data['catid']}' $selected>{$data['catname']}</option>";
                }
            } else {
                echo "<option value=''>No Category Found</option>";
            }  
            ?>
        </select>
        <input type="submit" name="<?php echo $editid ? 'update' : 'add'; ?>" value="<?php echo $editid ? 'Update Microgreen' : 'Add Microgreen'; ?>">
    </form>

    <!-- Section for viewing all microgreens -->
    <h2>View All Microgreens</h2>
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
                echo "<tr>
                    <td>{$data['microid']}</td>
                    <td>{$data['title']}</td>
                    <td>{$data['description']}</td>
                    <td><img src='../admin/uploads/images/{$data['image']}' alt='{$data['title']}'></td>
                    <td><video controls><source src='../admin/uploads/videos/{$data['video']}' type='video/mp4'></video></td>
                    <td>{$data['price']}</td>
                    <td>{$data['quantity']}</td>
                    <td>{$data['discount']}</td>
                    <td>{$data['available_stock']}</td>
                    <td>{$data['manufacture_date']}</td>
                    <td>{$data['expire_date']}</td>
                    <td>{$data['added_date']}</td>
                    <td>{$data['catname']}</td>
                    <td>
                        <a href='details.php?editid={$data['microid']}'>Edit</a>
                        <a href='details.php?deleteid={$data['microid']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>
                </tr>";
            }
        } else {
            echo '<tr><td colspan="11" class="no-records">No Microgreens Found</td></tr>';
        }
        ?>
    </table>
</div>

</body>
</html>

