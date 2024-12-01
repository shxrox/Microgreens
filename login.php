<?php
include('connect.php');
// Ensure the session is started in connect.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
    <style>
        /* General Styles */
body {
    font-family: 'Lato', sans-serif; /* Ensure the font family is applied */
    font-weight: 150; /* Regular weight */
    letter-spacing: 0.1rem; /* Slight letter spacing for clarity */
    font-size: 1.5rem;
    background-color: #f2f2f2;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    flex-direction: column; /* Stack the items vertically */
}

.container {
    text-align: center; /* Center-aligns text inside container */
}

h2 {
    margin-bottom: 20px;
    font-size: 24px; /* Adjust the size of the heading as needed */
    color: #0F2429; /* Adjust color if needed */
}

form {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
}

div {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="email"], 
input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

/* Button Styling */
button, .btn, .link-button {
    background-color:#0f2429; 
    color: rgb(255, 255, 255);
    padding: 12px 20px;
    border: 2px solid  #45A049;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
    font-weight: 600;
    transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease; 
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
    text-decoration: none; /* Ensure no underline on links */
    display: inline-block; /* Allow height and width settings on links */
    text-align: center; /* Center text in links */
}

button:hover, .btn:hover, .link-button:hover {
    background-color:#1A3942;
    color:  #32cd32;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); 
    transform: translateY(-2px); 
    font-size: 16px;
}

button:active, .btn:active, .link-button:active {
    background-color: #3E8E41; 
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); 
    transform: translateY(0); 
}

a {
    text-decoration: none;
    display: inline-block; /* To apply padding and margins */
    margin-top: 10px;
}

/* Optionally, for a link styled like a button */
a.btn-link {
    background-color: #1d381e; 
    color: rgb(255, 255, 255);
    padding: 12px 20px;
    border: 2px solid  #45A049;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease; 
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
    text-align: center; /* Center text */
    display: inline-block; /* Allows padding and margins */
}

a.btn-link:hover {
    background-color: #45A049;
    color:  #1d381e;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); 
    transform: translateY(-2px); 
    font-size: 16px;
}

a.btn-link:active {
    background-color: #3E8E41; 
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); 
    transform: translateY(0); 
}

     </style>
</head>
<body>
    <h2 class="container">Login Admin / User</h2>
    <form action="login.php" method="post">
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Email" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Password" required>
        </div>
        <div>
            <button type="submit" name="login">Login</button>
            <a href="register.php">Register</a>
        </div>
    </form>

    <?php
    if (isset($_POST['login'])) {
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);

        // Query to check user credentials in the 'users' table
        $sql_user = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
        $result_user = mysqli_query($con, $sql_user);

        // Query to check user credentials in the 'framer_registration' table
        $sql_farmer = "SELECT * FROM framer_registration WHERE email = '$email' AND password = '$password'";
        $result_farmer = mysqli_query($con, $sql_farmer);

        if (mysqli_num_rows($result_user) > 0 && mysqli_num_rows($result_farmer) > 0) {
            // User exists in both tables
            echo "<script>
                var role = prompt('You have multiple roles with this email. Enter 1 to log in as User or 2 to log in as Farmer');
                if (role == 1) {
                    window.location.href = 'login.php?role=user&email={$email}&password={$password}';
                } else if (role == 2) {
                    window.location.href = 'login.php?role=farmer&email={$email}&password={$password}';
                } else {
                    alert('Invalid choice. Please try again.');
                }
            </script>";
        } elseif (mysqli_num_rows($result_user) > 0) {
            // User only exists in 'users' table
            $data = mysqli_fetch_array($result_user);
            $role = $data['roteype'];

            $_SESSION['uid'] = $data['userid'];
            $_SESSION['type'] = $role;

            // Redirect based on role
            if ($role == 1) {
                echo "<script>alert('Admin login successfully!');</script>";
                echo "<script>window.location.href='admin/dashboard.php';</script>";
            } elseif ($role == 2) {
                echo "<script>alert('User login successfully!');</script>";
                echo "<script>window.location.href='index.php';</script>";
            } elseif ($role == 3) {
                echo "<script>alert('Staff login successfully!');</script>";
                echo "<script>window.location.href='staff/dashboard.php';</script>";
            } else {
                echo "<script>alert('Invalid role.');</script>";
            }
        } elseif (mysqli_num_rows($result_farmer) > 0) {
            // User only exists in 'framer_registration' table
            $data = mysqli_fetch_array($result_farmer);
            $role = $data['roteype'];

            $_SESSION['uid'] = $data['userid'];
            $_SESSION['type'] = $role;

            if ($role == 4) {
                echo "<script>alert('Farmer login successfully!');</script>";
                echo "<script>window.location.href='./famer/famerdashbord.php';</script>";
            } else {
                echo "<script>alert('Invalid role for farmer.');</script>";
            }
        } else {
            echo "<script>alert('Invalid email or password.');</script>";
        }
    }

    // Handle role redirection
    if (isset($_GET['role']) && isset($_GET['email']) && isset($_GET['password'])) {
        $role = $_GET['role'];
        $email = mysqli_real_escape_string($con, $_GET['email']);
        $password = mysqli_real_escape_string($con, $_GET['password']);

        if ($role == 'user') {
            $sql_user = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
            $result_user = mysqli_query($con, $sql_user);

            if (mysqli_num_rows($result_user) > 0) {
                $data = mysqli_fetch_array($result_user);
                $_SESSION['uid'] = $data['userid'];
                $_SESSION['type'] = $data['roteype'];
                echo "<script>alert('User login successfully!');</script>";
                echo "<script>window.location.href='index.php';</script>";
            }
        } elseif ($role == 'farmer') {
            $sql_farmer = "SELECT * FROM framer_registration WHERE email = '$email' AND password = '$password'";
            $result_farmer = mysqli_query($con, $sql_farmer);

            if (mysqli_num_rows($result_farmer) > 0) {
                $data = mysqli_fetch_array($result_farmer);
                $_SESSION['uid'] = $data['userid'];
                $_SESSION['type'] = $data['roteype'];
                echo "<script>alert('Farmer login successfully!');</script>";
                echo "<script>window.location.href='./famer/famerdashbord.php';</script>";
            }
        }
    }
    ?>

</body>
</html>
