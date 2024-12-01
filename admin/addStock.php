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
    <title>Microgreens Stock Management</title>
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

    </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="product-container">
    <h1>Manage Stock Microgreens</h1>

    <!-- Form Section -->
    <form action="addStock.php" method="post" enctype="multipart/form-data">
        <?php if(isset($_GET['editid'])): 
            $editid = mysqli_real_escape_string($con, $_GET['editid']);
            $sql = "SELECT * FROM stock WHERE stockid = '$editid'";
            $res = mysqli_query($con, $sql);
            $editdata = mysqli_fetch_array($res);
        ?>
            <input type="hidden" name="stockid" value="<?= htmlspecialchars($editdata['stockid']) ?>">
        <?php endif; ?>

        <div>
            <input type="text" name="title" value="<?= isset($editdata) ? htmlspecialchars($editdata['title']) : '' ?>" placeholder="Enter title" required>
        </div>
        <div>
            <textarea name="description" placeholder="Enter description" required><?= isset($editdata) ? htmlspecialchars($editdata['description']) : '' ?></textarea>
        </div>
        <div>
            <input type="text" name="price" value="<?= isset($editdata) ? htmlspecialchars($editdata['price']) : '' ?>" placeholder="Enter price" required>
        </div>
        <div>
            <input type="number" name="quantity" value="<?= isset($editdata) ? htmlspecialchars($editdata['quantity']) : '' ?>" placeholder="Enter quantity" required>
        </div>
        <div>
            <input type="number" name="available_stock" value="<?= isset($editdata) ? htmlspecialchars($editdata['available_stock']) : '' ?>" placeholder="Enter available stock" required>
        </div>
        <div>
            <label for="manufacture_date">Manufacture Date:</label>
            <input type="date" name="manufacture_date" value="<?= isset($editdata) ? htmlspecialchars($editdata['manufacture_date']) : '' ?>" required>
        </div>
        <div>
            <label for="expire_date">Expiration Date:</label>
            <input type="date" name="expire_date" value="<?= isset($editdata) ? htmlspecialchars($editdata['expire_date']) : '' ?>" required>
        </div>
        <div>
            <label for="added_date">Added Date:</label>
            <input type="date" name="added_date" value="<?= isset($editdata) ? htmlspecialchars($editdata['added_date']) : '' ?>" required>
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
                <th>Stock ID</th>
                <th>Microgreens Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Available Stock</th>
                <th>Manufacture Date</th>
                <th>Expire Date</th>
                <th>Added Date</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
            <?php
            $sql = "SELECT stock.*, categories.catname FROM stock INNER JOIN categories ON stock.catid = categories.catid";
            $res = mysqli_query($con, $sql);
            if(mysqli_num_rows($res) > 0){
                while($data = mysqli_fetch_array($res)){
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($data['stockid']) ?></td>
                        <td><?= htmlspecialchars($data['title']) ?></td>
                        <td><?= htmlspecialchars($data['description']) ?></td>
                        <td><?= htmlspecialchars($data['price']) ?></td>
                        <td><?= htmlspecialchars($data['quantity']) ?></td>
                        <td><?= htmlspecialchars($data['available_stock']) ?></td>
                        <td><?= htmlspecialchars($data['manufacture_date']) ?></td>
                        <td><?= htmlspecialchars($data['expire_date']) ?></td>
                        <td><?= htmlspecialchars($data['added_date']) ?></td>
                        <td><?= htmlspecialchars($data['catname']) ?></td>
                        <td class="action-links">
                            <a href="?editid=<?= htmlspecialchars($data['stockid']) ?>">Edit</a>
                            <a class="delete" href="?deleteid=<?= htmlspecialchars($data['stockid']) ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='11'>No stocks found.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>

<?php
// Handle adding a new stock entry
if(isset($_POST['add'])){
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $quantity = mysqli_real_escape_string($con, $_POST['quantity']);
    $available_stock = mysqli_real_escape_string($con, $_POST['available_stock']);
    $manufacture_date = mysqli_real_escape_string($con, $_POST['manufacture_date']);
    $expire_date = mysqli_real_escape_string($con, $_POST['expire_date']);
    $added_date = mysqli_real_escape_string($con, $_POST['added_date']);
    $catid = mysqli_real_escape_string($con, $_POST['catid']);

    $sql = "INSERT INTO stock (title, description, price, quantity, available_stock, manufacture_date, expire_date, added_date, catid) 
            VALUES ('$title', '$description', '$price', '$quantity', '$available_stock', '$manufacture_date', '$expire_date', '$added_date', '$catid')";
    
    if(mysqli_query($con, $sql)){
        echo "<script> alert('Stock added successfully!'); window.location.href='addStock.php'; </script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// Handle updating an existing stock entry
if(isset($_POST['update'])){
    $stockid = mysqli_real_escape_string($con, $_POST['stockid']);
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $quantity = mysqli_real_escape_string($con, $_POST['quantity']);
    $available_stock = mysqli_real_escape_string($con, $_POST['available_stock']);
    $manufacture_date = mysqli_real_escape_string($con, $_POST['manufacture_date']);
    $expire_date = mysqli_real_escape_string($con, $_POST['expire_date']);
    $added_date = mysqli_real_escape_string($con, $_POST['added_date']);
    $catid = mysqli_real_escape_string($con, $_POST['catid']);

    $update_sql = "UPDATE stock 
                   SET title='$title', description='$description', price='$price', quantity='$quantity', available_stock='$available_stock', manufacture_date='$manufacture_date', expire_date='$expire_date', added_date='$added_date', catid='$catid' 
                   WHERE stockid='$stockid'";
    
    if(mysqli_query($con, $update_sql)){
        echo "<script> alert('Stock updated successfully!'); window.location.href='addStock.php'; </script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// Handle deleting a stock entry
if(isset($_GET['deleteid'])){
    $deleteid = mysqli_real_escape_string($con, $_GET['deleteid']);
    
    $sql = "DELETE FROM stock WHERE stockid='$deleteid'";
    
    if(mysqli_query($con, $sql)){
        echo "<script> alert('Stock deleted successfully!'); window.location.href='addStock.php'; </script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
