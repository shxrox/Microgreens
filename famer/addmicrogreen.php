<?php
include('../connect.php');

if (isset($_GET['delid'])) {
    $delid = mysqli_real_escape_string($con, $_GET['delid']);

    // SQL query to delete the record
    $sql = "DELETE FROM farmer_micros WHERE microid = '$delid'";
    
    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Record deleted successfully'); window.location.href='addmicrogreen.php';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize form inputs
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $phone = mysqli_real_escape_string($con, $_POST['Phone']);
    $location = mysqli_real_escape_string($con, $_POST['Location']);
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $quantity = mysqli_real_escape_string($con, $_POST['quantity']);
    $discount = mysqli_real_escape_string($con, $_POST['discount']);
    $available_stock = mysqli_real_escape_string($con, $_POST['available_stock']);
    $manufacturing_date = mysqli_real_escape_string($con, $_POST['manufacturing_date']);
    $expiry_date = mysqli_real_escape_string($con, $_POST['expiry_date']);
    $catid = mysqli_real_escape_string($con, $_POST['catid']);
    $approved = isset($_POST['approved']) ? 1 : 0;
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($imageTmp, "../admin/uploads/images/" . $image);
    }
    
    // Handle video upload
    $video = '';
    if (isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
        $video = $_FILES['video']['name'];
        $videoTmp = $_FILES['video']['tmp_name'];
        move_uploaded_file($videoTmp, "../admin/uploads/videos/" . $video);
    }
    
    if (isset($_POST['microid'])) {
        // Update existing record
        $microid = mysqli_real_escape_string($con, $_POST['microid']);
        $sql = "UPDATE farmer_micros SET 
            title='$title', 
            description='$description', 
            Phone='$phone', 
            Location='$location', 
            image='$image', 
            video='$video', 
            price='$price', 
            quantity='$quantity', 
            discount='$discount', 
            available_stock='$available_stock', 
            manufacturing_date='$manufacturing_date', 
            expiry_date='$expiry_date', 
            catid='$catid', 
            approved='$approved' 
            WHERE microid='$microid'";
    } else {
        // Insert new record
        $sql = "INSERT INTO farmer_micros 
            (title, description, Phone, Location, image, video, price, quantity, discount, available_stock, manufacturing_date, expiry_date, catid, approved) 
            VALUES 
            ('$title', '$description', '$phone', '$location', '$image', '$video', '$price', '$quantity', '$discount', '$available_stock', '$manufacturing_date', '$expiry_date', '$catid', '$approved')";
    }

    // Execute query
    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Data saved successfully'); window.location.href='addmicrogreen.php';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Microgreens Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        body {
            font-family: 'Lato', sans-serif; 
    
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        h2 {
            font-family: 'Lato', sans-serif; 
    font-weight: 500; 
    font-size: 3rem; 
            text-align: center;
            margin: 20px 0;
            color: #0F2429;
        }
        form {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        form div {
            display: flex;
            flex-direction: column;
        }
        form input[type="text"],
        form input[type="number"],
        form input[type="date"],
        form textarea,
        form select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 8px;
            font-size: 16px;
        }
        form input[type="file"] {
            border: none;
            padding: 8px;
        }
       /* Form submit button styling */
form input[type="submit"] {
    background-color: #0F2429; /* Base color */
    color: white;
    padding: 12px 40px; /* Adjusted padding to match button styles */
    border: none;
    border-radius: 4px; /* Rounded corners for consistency */
    cursor: pointer;
    font-size: 18px; /* Larger font size for prominence */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Consistent font */
    transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease; /* Transition effects */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

/* Hover effect for form submit button */
form input[type="submit"]:hover {
    background-color: #1A3942; /* Darker background color on hover */
    color: #32CD32; /* Change text color on hover */
    letter-spacing: 1px; /* Adjusted letter spacing for consistency */
    transform: scale(1.08); /* Slightly enlarge button on hover */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* More pronounced shadow */
}

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #0F2429;
            color: white;
        }
        td img {
            width: 80px;
            border-radius: 4px;
        }
        td video {
            width: 150px;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .action-buttons a {
            text-decoration: none;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .action-buttons a.edit {
            background-color: #4CAF50;
        }
        .action-buttons a.edit:hover {
            background-color: #45a049;
        }
        .action-buttons a.delete {
            background-color: #f44336;
        }
        .action-buttons a.delete:hover {
            background-color: #e53935;
        }
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>

<h2>Manage Microgreens</h2>

<form action="addmicrogreen.php" method="post" enctype="multipart/form-data">
    <?php
    if(isset($_GET['editdata'])){
        $editdata = mysqli_real_escape_string($con, $_GET['editdata']);
        $sql = "SELECT * FROM farmer_micros WHERE microid = '$editdata'";
        $res = mysqli_query($con, $sql);
        $editdata = mysqli_fetch_array($res);
    }
    ?>

    <?php if(isset($editdata)): ?>
        <input type="hidden" name="microid" value="<?= htmlspecialchars($editdata['microid']) ?>">
    <?php endif; ?>

    <div>
        <label>Title</label>
        <input type="text" name="title" value="<?= isset($editdata) ? htmlspecialchars($editdata['title']) : '' ?>" placeholder="Enter title" required>
    </div>
    <div>
        <label>Description</label>
        <textarea name="description" placeholder="Enter description" required><?= isset($editdata) ? htmlspecialchars($editdata['description']) : '' ?></textarea>
    </div>
   
    
    <div>
        <label>Image</label>
        <input type="file" name="image" accept="image/*">
        <?php if(isset($editdata) && $editdata['image']): ?>
            <img src="./admin/uploads/images/<?= htmlspecialchars($editdata['image']) ?>" width="100" />
        <?php endif; ?>
    </div>
    <div>
        <label>Video</label>
        <input type="file" name="video" accept="video/*">
        <?php if(isset($editdata) && $editdata['video']): ?>
            <video width="320" height="240" controls>
                <source src="./admin/uploads/videos/<?= htmlspecialchars($editdata['video']) ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        <?php endif; ?>
    </div>
    <div>
        <label>Price</label>
        <input type="text" name="price" value="<?= isset($editdata) ? htmlspecialchars($editdata['price']) : '' ?>" placeholder="Enter price" required>
    </div>
    <div>
        <label>Quantity</label>
        <input type="number" name="quantity" value="<?= isset($editdata) ? htmlspecialchars($editdata['quantity']) : '' ?>" placeholder="Enter quantity" required>
    </div>
    <div>
        <label>Discount</label>
        <input type="text" name="discount" value="<?= isset($editdata) ? htmlspecialchars($editdata['discount']) : '' ?>" placeholder="Enter discount (optional)">
    </div>
    <div>
        <label>Available Stock</label>
        <input type="number" name="available_stock" value="<?= isset($editdata) ? htmlspecialchars($editdata['available_stock']) : '' ?>" placeholder="Enter available stock" required>
    </div>
    <div>
        <label>Manufacturing Date</label>
        <input type="date" name="manufacturing_date" value="<?= isset($editdata) ? htmlspecialchars($editdata['manufacturing_date']) : '' ?>" required>
    </div>
    <div>
        <label>Expiry Date</label>
        <input type="date" name="expiry_date" value="<?= isset($editdata) ? htmlspecialchars($editdata['expiry_date']) : '' ?>" required>
    </div>
    <div>
        <label>Location</label>
        <input type="text" id="Location" name="Location" placeholder="123 Street, City, Country" required><?= isset($editdata) ? htmlspecialchars($editdata['Location']) : '' ?>
    </div>
    <div>
        <label>Phone</label>
        <input type="Phone" id="Phone" name="Phone" placeholder="123-456-7890" required maxlength="10" minlength="10"><?= isset($editdata) ? htmlspecialchars($editdata['Phone']) : '' ?>
        
    </div>
    
    <div>
        <label>Category</label>
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
    <div class="form-check">
        <label>
            <input type="checkbox" name="approved" <?= isset($editdata) && $editdata['approved'] ? 'checked' : '' ?>> Approved
        </label>
    </div>
    <div>
        <input type="submit" value="<?= isset($editdata) ? 'Update' : 'Add' ?>" name="<?= isset($editdata) ? 'update' : 'add' ?>">
    </div>
</form>

<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Phone</th>
            <th>Location</th>
            <th>Image</th>
            <th>Video</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Discount</th>
            <th>Stock</th>
            <th>Manufacture Date</th>
            <th>Expiry Date</th>
            <th>Category</th>
            <th>Approved</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM farmer_micros";
        $res = mysqli_query($con, $sql);
        if(mysqli_num_rows($res) > 0){
            while($data = mysqli_fetch_array($res)){
                echo "<tr>
                    <td>".htmlspecialchars($data['title'])."</td>
                    <td>".htmlspecialchars($data['description'])."</td>
                    <td>".htmlspecialchars($data['Phone'])."</td>
                    <td>".htmlspecialchars($data['Location'])."</td>

                    <td><img src='../admin/uploads/images/".htmlspecialchars($data['image'])."' /></td>
                    <td>";
                    if($data['video']){
                        echo "<video controls>
                                <source src='../admin/uploads/videos/".htmlspecialchars($data['video'])."' type='video/mp4'>
                                Your browser does not support the video tag.
                              </video>";
                    } else {
                        echo "No Video";
                    }
                    echo "</td>
                    <td>".htmlspecialchars($data['price'])."</td>
                    <td>".htmlspecialchars($data['quantity'])."</td>
                    <td>".htmlspecialchars($data['discount'])."</td>
                    <td>".htmlspecialchars($data['available_stock'])."</td>
                    <td>".htmlspecialchars($data['manufacturing_date'])."</td>
                    <td>".htmlspecialchars($data['expiry_date'])."</td>
                    <td>".htmlspecialchars($data['catid'])."</td>
                    <td>".($data['approved'] ? 'Yes' : 'No')."</td>
                    <td class='action-buttons'>
                        <a href='?editdata=".htmlspecialchars($data['microid'])."' class='edit'>Edit</a>
                        <a href='addmicrogreen.php?delid=".htmlspecialchars($data['microid'])."' class='delete' onclick='return confirm(\"Are you sure you want to delete this?\");'>Delete</a>

                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='13' style='text-align:center;'>No records found</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php include('footer.php') ?>
<?php include('chat.php') ?>
<?php include('social.php') ?>
<?php include('weather.php') ?>
</body>
</html>
