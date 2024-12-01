<?php include('connect.php')  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    
    <title>Register</title>
    <style>
        body {
    font-family: 'Lato', sans-serif; /* Ensure the font family is applied */
    font-weight: 300; /* Regular weight */
    
    
    letter-spacing: 0.1rem; /* Slight letter spacing for clarity */
    text-transform: uppercase; /* Uppercase text for emphasis */
    text-align: center; /* Center the text */
    margin: 0; /* Remove default margin */
    font-size: 0.8rem;



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

/* Form Styles */
form {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
    margin: 0 auto; /* Center the form */
}

div {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"], 
input[type="email"], 
input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
/* Button Styling */
.btn-reg {
    background-color: #0f2429; /* Dark green background */
    color: #ffffff; /* White text color */
    padding: 12px 20px; /* Padding around the text */
    border: 2px solid #45A049; /* Light green border */
    border-radius: 5px; /* Rounded corners */
    cursor: pointer; /* Pointer cursor on hover */
    width: 100%; /* Full width */
    font-size: 16px; /* Font size */
    font-weight: 600; /* Bold text */
    transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease; /* Smooth transitions */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Shadow effect */
    text-align: center; /* Center text */
    display: inline-block; /* Allows height and width settings */
}

.btn-reg:hover {
    background-color: #1A3942; /* Light green background on hover */
    color: #32CD32; /* Dark green text color on hover */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* Stronger shadow on hover */
    transform: translateY(-2px); /* Slight lift effect */
    font-size: 16px; /* Keep the font size the same */
}

.btn-reg:active {
    background-color:#1A3942;
    color:  #32cd32;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); 
    transform: translateY(-2px); 
    font-size: 16px;
}
/* Styled link */
a.styled-link {
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

/* Hover state for styled link */
button:hover, .btn:hover, .link-button:hover {
    background-color:#1A3942;
    color:  #32cd32;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); 
    transform: translateY(-2px); 
    font-size: 16px;
}
/* Active state for styled link */
a.styled-link:active {
    background-color: #3E8E41; /* Even darker green on click */
    color: #ffffff; /* White text color on click */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Reduced shadow on click */
    transform: translateY(0); /* Return to original position */
}

     </style>
</head>
<body>
    

<section id="team" class="team section-bg">
      <div class="container aos-init aos-animate" data-aos="fade-up">

        <div class="section-title">
          <h2>Register for Buying Products</h2>
        </div>

        <div class="row">

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
          <form action="register.php" method="post" role="form" class="php-email-form">
              <div class="row">
                
                <div class="col form-group mb-3">
                  <input type="text" class="form-control" name="name" id="name" placeholder="Your Name" required="">
                </div>
              </div>
                <div class="col form-group mb-3">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required="">
                </div>

             
              <div class="form-group mb-3">
                <input type="text" class="form-control" name="password" id="password" placeholder="Your Password" required="">
              </div>

              <div class="text-center"><button type="submit" class="btn-reg" name="register">Register</button></div>
            </form>
            <a href="register_framer.php">Register as a Famer / Seller</a>
          </div>

        

        </div>

      </div>
</section>

</body>
</html>

<?php

if (isset($_POST['register'])) {

    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email already exists
    $check_email_query = "SELECT * FROM `users` WHERE email='$email'";
    $check_email_result = mysqli_query($con, $check_email_query);

    if (mysqli_num_rows($check_email_result) > 0) {
        // Email already exists
        echo "<script> alert('Email is already registered! Please try a different email or log in.'); </script>";
    } else {
        // Proceed with registration
        $sql = "INSERT INTO `users`(`name`, `email`, `password`, `roteype`) VALUES ('$name','$email','$password','2')";

        if (mysqli_query($con, $sql)) {
            echo "<script> alert('User registered successfully!'); </script>";
            echo "<script> window.location.href='login.php'; </script>";
        } else {
            echo "<script> alert('User registration failed. Please try again.'); </script>";
        }
    }
}

?>

