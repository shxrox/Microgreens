<?php
include('../connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        #main-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: green;
            font-size: 2em;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
            text-align: center;
        }

        form div {
            margin-bottom: 10px;
        }

        input[type="text"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1em;
        }

        input[type="submit"] {
            background-color: green;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #005700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: green;
            color: #fff;
            font-size: 1em;
        }

        .action-link {
            color: green;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .action-link:hover {
            color: #005700;
        }

        .no-data {
            text-align: center;
            font-style: italic;
            color: #999;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>

    <div id="main-container">
        <h2>Manage Categories</h2>
        <?php if (isset($_GET['editid'])): ?>
            <?php
            $editid = mysqli_real_escape_string($con, $_GET['editid']);
            $sql = "SELECT * FROM `categories` WHERE catid = '$editid'";
            $res = mysqli_query($con, $sql);
            $editdata = mysqli_fetch_array($res);
            ?>
            <form id="edit-form" action="categories.php" method="post">
                <input type="hidden" name="catid" value="<?= htmlspecialchars($editdata['catid']) ?>">
                <div>
                    <input type="text" id="catname-input" name="catname" value="<?= htmlspecialchars($editdata['catname']) ?>" placeholder="Enter category" required>
                </div>
                <div>
                    <input type="submit" id="update-btn" value="Update" name="update">
                </div>
            </form>
        <?php else: ?>
            <form id="add-form" action="categories.php" method="post">
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
                                <a href="categories.php?editid=<?= htmlspecialchars($data['catid']) ?>" class="action-link">Edit</a> |
                                <a href="categories.php?deleteid=<?= htmlspecialchars($data['catid']) ?>" class="action-link">Delete</a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="3" class="no-data">No categories found</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($con, $_POST['catname']);
    $sql = "INSERT INTO `categories` (`catname`) VALUES ('$name')";
    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Category added');</script>";
        echo "<script>window.location.href='categories.php';</script>";
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
        echo "<script>window.location.href='categories.php';</script>";
    } else {
        echo "<script>alert('Category not updated');</script>";
    }
}

if (isset($_GET['deleteid'])) {
    $deleteid = mysqli_real_escape_string($con, $_GET['deleteid']);
    $sql = "DELETE FROM `categories` WHERE `catid` = '$deleteid'";
    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Category deleted');</script>";
        echo "<script>window.location.href='categories.php';</script>";
    } else {
        echo "<script>alert('Category not deleted');</script>";
    }
}
?>
