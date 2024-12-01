<?php
include('../connect.php');

// Check for POST requests
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($con, $_POST['catname']);
    $sql = "INSERT INTO `categories` (`catname`) VALUES ('$name')";
    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Category added');</script>";
        echo "<script>window.location.href='category.php';</script>";
    } else {
        echo "<script>alert('Category not added');</script>";
    }
}

if (isset($_POST['update'])) {
    $catid = mysqli_real_escape_string($con, $_POST['catid']);
    $name = mysqli_real_escape_string($con, $_POST['catname']);
    $sql = "UPDATE `categories` SET `catname` = '$name' WHERE `catid` = '$catid'";
    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Category updated');</script>";
        echo "<script>window.location.href='category.php';</script>";
    } else {
        echo "<script>alert('Category not updated');</script>";
    }
}

if (isset($_GET['deleteid'])) {
    $deleteid = mysqli_real_escape_string($con, $_GET['deleteid']);
    $sql = "DELETE FROM `categories` WHERE `catid` = '$deleteid'";
    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Category deleted');</script>";
        echo "<script>window.location.href='category.php';</script>";
    } else {
        echo "<script>alert('Category not deleted');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../admin/categories.css">
    <title>Manage Categories</title>
</head>
<body>

<?php include('header.php'); ?>

<div id="main-container">
    <?php if (isset($_GET['editid'])): ?>
        <?php
        $editid = mysqli_real_escape_string($con, $_GET['editid']);
        $sql = "SELECT * FROM `categories` WHERE catid = '$editid'";
        $res = mysqli_query($con, $sql);
        $editdata = mysqli_fetch_array($res);
        ?>
        <form id="edit-form" action="category.php" method="post">
            <input type="hidden" name="catid" value="<?= htmlspecialchars($editdata['catid']) ?>">
            <div>
                <input type="text" id="catname-input" name="catname" value="<?= htmlspecialchars($editdata['catname']) ?>" placeholder="Enter category" required>
            </div>
            <div>
                <input type="submit" id="update-btn" value="Update" name="update">
            </div>
        </form>
    <?php else: ?>
        <form id="add-form" action="category.php" method="post">
            <div>
                <input type="text" id="catname-input" name="catname" placeholder="Enter category" required>
            </div>
            <div>
                <input type="submit" id="add-btn" value="Add" name="add">
            </div>
        </form>
    <?php endif; ?>

    <table id="categories-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM `categories`";
            $res = mysqli_query($con, $sql);
            if (mysqli_num_rows($res) > 0) {
                while ($data = mysqli_fetch_array($res)) {
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($data['catid']) ?></td>
                        <td><?= htmlspecialchars($data['catname']) ?></td>
                        <td>
                            <a href="category.php?editid=<?= htmlspecialchars($data['catid']) ?>" class="action-link">Edit</a>
                            <a href="category.php?deleteid=<?= htmlspecialchars($data['catid']) ?>" class="action-link" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo '<tr><td colspan="3">No categories found</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>


</body>
</html>