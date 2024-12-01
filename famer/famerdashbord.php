<?php
// Include your database connection and header
include('connect.php');
include('header.php');

$search_query = '';
$selected_catid = '';
$is_logged_in = isset($_SESSION['uid']); // Check if user is logged in

// Check if form is submitted and set search variables
if (isset($_POST['btnSearch'])) {
    $search_query = mysqli_real_escape_string($con, $_POST['micro_search'] ?? '');
    $selected_catid = mysqli_real_escape_string($con, $_POST['catid'] ?? '');
}
?>

<style>
/* General Styles */
body {
   
    line-height: 1.6;
    margin: 0;
    padding: 0;
    background-color: #e9ecef;
    color: #333;
}

body {
   
   line-height: 1.6;
   margin: 0;
   padding: 0;
   background-color: #e9ecef;
   color: #333;
   
}

/* Container */
.container {
   width: 90%;
   max-width: 1400px;
   margin: auto;
   overflow: hidden;
}

/* Welcome Image Styling */
.wlcm_img {
   position: relative;
   height: 100vh;
   width: 100%;
   overflow: hidden;
   margin-bottom: 20px;
}

.wlcm_img img {
   width: 100%;
   height: 100%;
 
   top: 0;
   left: 0;
   z-index: 0;
}

.wlcm_img .overlay {
   /*position: absolute;
   top: 0;
   left: 0;
   width: 100%;
   height: 100%;
   background: rgba(0, 0, 0, 0.5); /* Darker overlay 
   z-index: 1;*/
}

.wlcm_img .centered-text {
   
   position: absolute;
   top: 35%;
   left: 45%;
   transform: translate(-50%, -50%);
   letter-spacing: 1rem;
  
   z-index: 2;
}





.centered-text h1 {
    font-family: 'Lato', sans-serif; /* Ensure the font family is applied */
    font-weight: 300; /* Regular weight */
    font-size: 7rem; /* Adjust the size to your preference */
    color: #ffffff; /* Text color */
    letter-spacing: 0.1rem; /* Slight letter spacing for clarity */
    text-transform: uppercase; /* Uppercase text for emphasis */
    text-align: left; /* Center the text */
    margin: 0; /* Remove default margin */
}


.centered-text p {
    font-family: 'Lato', sans-serif; /* Ensure the font family is applied */
    font-size:15px; /* Adjust the size to your preference */
    color: #ffffff; /* Text color */
    font-weight: bolder; /* Use bolder for text weight */
    text-align: left;
    margin-top: 100px; /* Adjust spacing from the top as needed */
    position: relative;
    letter-spacing: 0.2rem; /* Slight letter spacing for clarity */
    
   
    border-radius: 8px; /* Optional: Rounded corners for a smoother look */
    max-width: 80%; /* Optional: Adjust width as needed */
    margin: 0 auto; /* Center the element horizontally */
}




.title{
    display: flex;
    justify-content: center;

    font-family: 'Lato', sans-serif; /* Ensure the font family is applied */
    font-weight: 300; /* Regular weight */
    
    
    letter-spacing: 0.1rem; /* Slight letter spacing for clarity */
    text-transform: uppercase; /* Uppercase text for emphasis */
    text-align: center; /* Center the text */
    margin: 0; /* Remove default margin */
    font-size: 5rem;
    color: #0F2429;
}

/* Search Form Styling */
.pr-srch{
    display: flex;
    justify-content: center;
}





.row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    align-items: center;
    margin-bottom: 20px;
    background-color: #f9f9f9;
    padding: 20px;
    width: 600px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.form-group {
    flex: 1;
    max-width: 48%;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.form-control {
    width: 100%;
    padding: 12px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
    background-color: #fff;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.6);
    outline: none;
}

#form-control-left {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    width: 200px;
    background-color: #e9f5ff;
}

#form-control-right {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    width: 300px;
    background-color: #eef6e9;
}

.submit {
    flex: 1 1 100%;
    text-align: center;
    margin-top: 20px;
}

/* General Button Styling */
.btn {
    padding: 12px 40px; /* Standard padding for buttons */
    background-color: #0F2429; /* Primary button color */
    color: #fff; /* Text color */
    border: none;
    cursor: pointer;
    font-size: 18px; /* Slightly larger font for prominence */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Font family */
    transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease; /* Smooth transitions */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    border-radius: 4px; /* Rounded corners for consistency */
    text-align: center; /* Center text alignment */
}

/* Button Hover Effects */
.btn:hover {
    background-color: #1A3942; /* Darker shade for hover */
    letter-spacing: 1px; /* Adjust letter spacing for emphasis */
    transform: scale(1.05); /* Slightly increased scale on hover */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* More pronounced shadow on hover */
    color: #32CD32; /* Light green text color on hover */
}


/* Card Container */
/* Flex container for product cards */
.product-container {
    display: flex;
    flex-wrap: nowrap; /* Prevent wrapping to ensure all cards stay in a single row */
    overflow-x: auto; /* Allow horizontal scrolling if needed */
    gap: 20px; /* Space between cards */
    padding: 20px;
    /* Add overflow-y hidden if you want to hide vertical scrollbar */
}

/* Product card styling */
.product-card {
    position: relative;
    width: 300px; /* Adjust width as needed */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow for initial state */
    flex-shrink: 0; /* Prevent cards from shrinking */
}

/* Image styling */
.product-card img {
    width: 100%;
    height: 200px;
    display: block;
    transition: opacity 0.3s ease;
}

/* Styling for product info */
.product-info {
    padding: 15px;
    text-align: center;
}

/* Hover effects for product card */
.product-card:hover {
    transform: scale(1.05); /* Slightly enlarge the card */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Add shadow */
}

/* Overlay effect on hover */
.product-card:hover::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Dark overlay */
    transition: opacity 0.3s ease;
}

/* View product button styling */
.view-product-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 10; /* Ensure button is above the overlay */
}

.product-card:hover .view-product-btn {
    opacity: 1; /* Show button on hover */
}

.view-btn {
    background-color: #32CD32;
    color: white;
    padding: 5px 10px; /* Adjust padding */
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
    display: inline-block;
    transition: background-color 0.3s ease;
    border-radius: 4px; /* Rounded corners */
}

.view-btn:hover {
    background-color: #28a745; /* Slightly darker green on hover */
    letter-spacing: 1px; /* Adjust letter spacing */
}

</style>
<html>
    <body>
<div class="wlcm_img">
    <div class="overlay"></div>
    <img src="../images/Untitled-3.png" alt="Welcome Image">
    <div class="centered-text" id="h1">
        <h1><b>MICROGREENS</b></h1>
        <p> To grow and supply high-quality
            microgreens that enhance the 
            <br>health and flavor of every meal</p>
    </div>
</div>
<section id="team" class="team section-bg">
    <div class="container" data-aos="fade-up">
        <div class="title">
            <h2>OUR PRODUCTS</h2>
        </div>

        <form action="famerdashbord.php" method="post" class="pr-srch">
            <div class="row">

                <div class="form-group">
                    <label for="form-control-left">Select Category:</label>
                    <select name="catid" id="form-control-left" class="form-control">
                        <option value="">Select Category</option>
                        <?php
                        $sql = "SELECT * FROM categories";
                        $res = mysqli_query($con, $sql);
                        if (mysqli_num_rows($res) > 0) {
                            while ($data = mysqli_fetch_array($res)) {
                                $selected = ($selected_catid == $data['catid']) ? 'selected' : '';
                                echo "<option value=\"{$data['catid']}\" $selected>{$data['catname']}</option>";
                            }
                        } else {
                            echo "<option value=\"\">No Category found</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="form-control-right">Search Microgreen Title:</label>
                    <input type="text" id="form-control-right" name="micro_search" value="<?= htmlspecialchars($search_query) ?>" placeholder="Search Microgreen Title" class="form-control">
                </div>

                <div class="submit">
                    <input type="submit" name="btnSearch" value="Search" class="btn btn-primary">
                </div>

            </div>
        </form>

        <div class="product-container">
            <?php
            $sql = "SELECT micro.*, categories.catname
                    FROM micro
                    INNER JOIN categories ON categories.catid = micro.catid
                    WHERE micro.title LIKE '%$search_query%' AND (micro.catid = '$selected_catid' OR '$selected_catid' = '')
                    ORDER BY micro.microid DESC";
            $res = mysqli_query($con, $sql);
            if (mysqli_num_rows($res) > 0) {
                while ($data = mysqli_fetch_array($res)) {
                    $max_quantity = $data['available_stock'];
                    $discount = $data['discount'];
            ?>
                <div class="product-card">
                    <div class="product-info">
                        <img src="../admin/uploads/images/<?= htmlspecialchars($data['image']) ?>" alt="<?= htmlspecialchars($data['title']) ?>">
                        <h4><?= htmlspecialchars($data['title']) ?></h4>
                        <p>Price: <?= htmlspecialchars($data['price']) ?></p>
                        <span><?= htmlspecialchars($data['catname']) ?></span>
                        <p>Available Stock: <?= htmlspecialchars($max_quantity) ?></p>
                        <?php if ($discount > 0): ?>
                            <p>Discount: <?= htmlspecialchars($discount) ?>%</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
                }
            } else {
                echo "<p>No microgreens found</p>";
            }
            ?>
        </div>
    </div>
</section>
<?php include('weather.php') ?>
<?php include('footer.php'); ?>
<?php include('chat.php'); ?>
<?php include('social.php'); ?>

        </body>
        </html>
