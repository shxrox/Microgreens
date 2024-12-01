<?php 

include('connect.php');

if(!isset($_SESSION['uid'])){
    echo "<script> window.location.href='login.php'; </script>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $uid = $_SESSION['uid'];

    $update_sql = "UPDATE users SET name='$name', email='$email' WHERE userid='$uid'";
    if (mysqli_query($con, $update_sql)) {
        echo "<script>alert('Profile updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating profile');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: 'Lato', sans-serif; /* Ensure the font family is applied */
    font-weight: 300; /* Regular weight */
    
    
    letter-spacing: 0.1rem; /* Slight letter spacing for clarity */
    text-transform: uppercase; /* Uppercase text for emphasis */
    text-align: center; /* Center the text */
    margin: 0; /* Remove default margin */
    font-size: 2rem;


            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .profile-container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-header h1 {
            color: #32cd32;
            font-size: 28px;
            margin: 0;
        }

        .profile-card {
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-card h2 {
            color: #32cd32;
            margin-bottom: 15px;
        }

        .profile-card p {
            font-size: 16px;
            margin: 10px 0;
        }

        .update-profile {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .update-profile h2 {
            color: #32cd32;
            margin-bottom: 15px;
        }

        .update-profile label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .update-profile input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .update-profile button {
    background-color: #0F2429; /* Primary button color */
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s, letter-spacing 0.3s; /* Ensure smooth transition */
    text-align: center;
}

.update-profile button:hover {
    background-color: #1A3942;; /* Darker shade on hover */
    color: #32CD32; /* Light green text color on hover */
    letter-spacing: 1px; /* Adjust letter-spacing to match */
}

#btn-logout {
    background-color: #0F2429; /* Primary button color */
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    display: block;
    width: 100%;
    margin-top: 20px;
    transition: background-color 0.3s, color 0.3s, letter-spacing 0.3s; /* Ensure smooth transition */
    text-align: center;
}

#btn-logout:hover {
    background-color:  #1A3942;; /* Darker shade on hover */
    color: #c82333; /* Red text color on hover */
    letter-spacing: 1px; /* Adjust letter-spacing to match */
}

    </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="profile-container">
    <div class="profile-header">
        <h1>User Profile</h1>
    </div>

    <div class="profile-card">
        <h2>Profile Information</h2>
        <?php
        $uid = $_SESSION['uid'];
        $sql = "SELECT * FROM users WHERE userid = '$uid'";
        $res = mysqli_query($con, $sql);
        if (mysqli_num_rows($res) > 0) {
            while ($data = mysqli_fetch_array($res)) {
        ?>
        <p><strong>ID:</strong> <?= htmlspecialchars($data['userid']) ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($data['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></p>
        <p><strong>Password:</strong> <?= htmlspecialchars($data['password']) ?></p>
        <?php
            }
        } else {
            echo '<p>No user found</p>';
        }
        ?>
    </div>

    <!-- Profile Update Form -->
    <div class="update-profile">
        <h2>Update Profile</h2>
        <form method="post" action="">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Update Profile</button>
        </form>
    </div>

    <button id="btn-logout">LOGOUT</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var logoutButton = document.getElementById('btn-logout');
    logoutButton.addEventListener('click', function() {
        window.location.href = 'logout.php'; // Ensure logout.php handles session destruction
    });
});
</script>

<?php include('footer.php'); ?>
<?php include('chat.php') ?>
<?php include('social.php') ?>

</body>
</html>


