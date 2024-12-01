<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">


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
        nav .dropdown {
            position: relative;
            display: inline-block;
        }
        nav .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: rgba(255, 255, 255, 0.9);
            margin-top: 5px;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 2;
            border-radius: 4px;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            pointer-events: none; /* Prevent immediate hide */
    }

        nav .dropdown:hover .dropdown-content,
        nav .dropdown-content:hover {
            display: block;
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto; /* Allow interactions */
    }

        nav .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            margin-left: -1px;
            display: block;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        nav .dropdown-content a:hover {
            border-radius: 4px;
        }
        nav .dropdown:hover .dropdown-content {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        nav .profile-icon {
            margin-left: 20px;
            cursor: pointer;
            transition: transform 0.3s ease, filter 0.3s ease;
            height: 20px;
        }
        nav .profile-icon:hover {
            transform: rotate(360deg); /* Rotate on hover */
            filter: brightness(1.2); /* Brighten icon on hover */
        }

     .cart-icon{
    height: 25px;
}

.cart-dropdown {
    display: none;
    position: absolute;
    top: 50px;
    right: 10px;
    width: 300px;
    max-height: 400px;
    background-color: rgba(0, 128, 0, 0.2); /* Transparent green background */
    border: 1px solid rgba(0, 128, 0, 0.5);
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0px 8px 24px rgba(0, 0, 0, 0.2);
    z-index: 1000;
    padding: 15px;
    overflow-y: auto;
}

.cart-dropdown h3 {
    margin-top: 0;
    font-size: 16px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
    margin-bottom: 15px;
    color: #333;
    text-align: center;
    font-weight: 600;
}

.cart-dropdown .cart-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
    padding: 10px;
    background-color: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    transition: background-color 0.3s ease;
}

.cart-dropdown .cart-item:hover {
    background-color: #f1f3f5; /* Light background on hover */
}

.cart-dropdown .cart-item img {
    width: 50px;
    height: 50px;
    border-radius: 4px; /* Slightly rounded image */
    margin-right: 15px;
}

.cart-dropdown .cart-item p {
    margin: 0;
    font-size: 14px;
    color: #555;
    flex: 1; /* Take up remaining space */
}

.cart-dropdown .buy-btn {
    background-color: #28a745; 
    color: white;
    border: none;
    padding: 6px 12px;
    font-size: 14px;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.cart-dropdown .buy-btn:hover {
    background-color: #218838; /* Darker green on hover */
    transform: scale(1.05); /* Slight zoom on hover */
}



    </style>

         <?php
         
          if(!isset($_SESSION['uid'])){
           
            echo '
          
            <nav>
        <div class="logo">
            <img src="./images/mg.png" alt="Logo">
        </div>
        <div class="nav-links">
            <a class="nav-link scrollto active" href="index.php">Home</a>
            <a class="nav-link scrollto" href="view_blogs.php">Blogs</a>
            <a class="nav-link scrollto" href="about.php">About</a>
            <div class="dropdown">
                <img src="./images/users.png" alt="Profile Icon" class="profile-icon">
                <div class="dropdown-content">
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                </div>
            </div>
        </div>
    </nav>
            
            
            ';
          }else{
            $type = $_SESSION['type'];
             if($type == 2){
              echo '
             <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<nav>
    <div class="logo">
        <img src="./images/mg.png" alt="Logo">
    </div>
    <div class="nav-links">
        <a class="nav-link scrollto active" href="index.php">Home</a>
        <a class="nav-link scrollto" href="view_blogs.php">Blogs</a>
        <a class="nav-link scrollto" href="feedback.php">FeedBack</a>
        <a class="nav-link scrollto" href="about.php">About</a>
        <a class="nav-link scrollto" href="payment_details.php">PaymentDetails</a>
        <a class="nav-link scrollto" href="viewprofile.php">Profile</a>
        <a class="nav-link scrollto" href="logout.php">Logout</a>
        <a class="nav-link scrollto" href="#" id="cart-button">
            <img src="./images/shopping-cart2.png" alt="" class="cart-icon">
        </a>
        <div id="cart-dropdown" class="cart-dropdown">
            <!-- Cart items will be dynamically loaded here -->
        </div>
    </div>
</nav>


             
              ';
             }
          }

         ?>
        
       
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>

    </div>
  </header>

  <script>
    $(document).ready(function() {
    $('#cart-button').on('click', function(e) {
        e.preventDefault();
        $('#cart-dropdown').toggle();

        $.ajax({
            url: 'get_cart_items.php',
            method: 'GET',
            success: function(response) {
                $('#cart-dropdown').html(response);
            },
            error: function() {
                $('#cart-dropdown').html('<p>Error loading cart items.</p>');
            }
        });
    });

    $(document).click(function(e) {
        if (!$(e.target).closest('#cart-button, #cart-dropdown').length) {
            $('#cart-dropdown').hide();
        }
    });
});

</script>