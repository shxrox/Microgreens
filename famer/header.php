
<style>
   nav {
            background-color: black;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }
        nav:hover {
            background-color: #0F2429; /* Darker green on hover */
        }
        .logo img {
            height: 40px;
            transition: transform 0.3s ease;
        }
        .logo img:hover {
            transform: scale(1.1); /* Slight zoom on hover */
            
        }
        nav .nav-links {
            display: flex;
            align-items: center;
        }
        nav .nav-links a {
            font-family: 'Lato', sans-serif; /* Ensure the font family is applied */
    font-weight: 300; /* Regular weight */
    font-size: 1rem; /* Adjust the size to your preference */
   
    letter-spacing: 0.1rem; /* Slight letter spacing for clarity */
   
   
    



           
            
            color: white;
            text-decoration: none;
            margin-left: 20px;
            position: relative;
            padding: 8px 0;
            transition: color 0.3s ease, transform 0.3s ease;
        }
        nav .nav-links a:hover {
            color: #32CD32; /* Yellow text on hover */
            transform: translateY(-2px); /* Slight lift on hover */
        }
        nav .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: #32CD32;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
        }
        nav .nav-links a:hover::after {
            visibility: visible;
            width: 100%; /* Underline grows on hover */
        }
</style>

<header id="header">
  <div class="header-container">

  <nav>
    <div class="logo">
        <img src="../images/mg.png" alt="Logo">
    </div>
    <div class="nav-links">
        <a class="nav-link scrollto active" href="famerdashbord.php">Home</a>
        <a class="nav-link" href="addmicrogreen.php">Sell Microgreens</a>
        <a class="nav-link scrollto" href="view_blogs.php">Blogs</a>
        <a class="nav-link scrollto" href="about.php">About</a>
        <a class="nav-link" href="submit_problem.php">Complain</a>
        <a class="nav-link" href="hotel.php">Hotel</a>
        <a class="nav-link scrollto" href="help.php">Crop Managment</a>
        <a class="nav-link scrollto" href="payment.php">PaymentDetails</a>
        <a class="nav-link scrollto" href=" ViewRules.php">Rules&Regulations</a>
       
        <a class="nav-link scrollto" href="logout.php">Logout</a>
    </div>
</nav>
 
  </div>
  
</header>
