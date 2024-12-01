<?php
include('../connect.php');

// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$submission_status = "";
$deletion_status = "";

// Handle form submission for new rule
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit') {
    $rule = mysqli_real_escape_string($con, $_POST['rule']);
    $updatedate = mysqli_real_escape_string($con, $_POST['update_date']);

    // Insert rule details into the database
    $sql = "INSERT INTO `rules&regulations` (rule, update_date) VALUES ('$rule', '$updatedate')";
    if (mysqli_query($con, $sql)) {
        $submission_status = "Rule submitted successfully.";
    } else {
        $submission_status = "Error: " . mysqli_error($con);
    }
}

// Handle rule deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $rule_id = intval($_POST['rule_id']);

    // Delete rule from the database
    $sql = "DELETE FROM `rules&regulations` WHERE ruleid = $rule_id";
    if (mysqli_query($con, $sql)) {
        $deletion_status = "Rule deleted successfully.";
    } else {
        $deletion_status = "Error: " . mysqli_error($con);
    }
}

// Retrieve data from the database
$sql = "SELECT * FROM `rules&regulations` ORDER BY `update_date` DESC";
$result = mysqli_query($con, $sql);

$data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
} else {
    echo "Error: " . mysqli_error($con);
}

// Close the connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rules & Regulations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        h1 {
            color: green;
            border-bottom: 2px solid #2d8f2d;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 600px;
            margin-bottom: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form {
            display: grid;
            gap: 15px;
            width: 100%;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        button {
            background-color: #2d8f2d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
            margin-top: 10px;
            align-self: center;
        }

        button:hover {
            background-color: #1b6b1b;
        }

        .alert {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            width: 100%;
            max-width: 600px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            max-width: 600px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2d8f2d;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td {
            font-size: 0.9em;
            color: #333;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container">
        <h1>Rules & Regulations</h1>

        <div class="form-wrapper">
            <?php if ($submission_status): ?>
                <div class="alert"><?php echo htmlspecialchars($submission_status, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <?php if ($deletion_status): ?>
                <div class="alert"><?php echo htmlspecialchars($deletion_status, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <form action="Rules.php" method="post">
                <input type="hidden" name="action" value="submit">
                <div class="form-group">
                    <label for="rule">Rule:</label>
                    <textarea id="rule" name="rule" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label for="update_date">Update Date:</label>
                    <input type="date" id="update_date" name="update_date">
                </div>

                <button type="submit">Submit</button>
            </form>
        </div>

        <?php if (empty($data)): ?>
            <div class="alert">No rules found.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Rule ID</th>
                        <th>Rule</th>
                        <th>Update Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['ruleid'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row['rule'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row['update_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <form action="Rules.php" method="post" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="rule_id" value="<?php echo htmlspecialchars($row['ruleid'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
