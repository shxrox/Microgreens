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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Microgreens Dashboard</title>
    <style>
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

     /* General form styling */
form {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Styling for input fields */
input[type="text"],
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

/* Input field focus effect */
input[type="text"]:focus,
select:focus {
    border-color: #0F2429;
    outline: none;
}

/* Styling for submit button */
input[type="submit"] {
    background-color: #0F2429; /* Primary button color */
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s, letter-spacing 0.3s; /* Ensure smooth transition */
    text-align: center;
    width: auto; /* Make sure the button width fits content */
}

/* Submit button hover effect */
input[type="submit"]:hover {
    background-color: #0F3729; /* Darker shade on hover */
    color: #32CD32; /* Light green text color on hover */
    letter-spacing: 1px; /* Adjust letter-spacing to match */
}

/* Additional styling for form groups */
.form-group {
    margin-bottom: 20px;
}
   
      

        .slider-container {
            position: relative;
            overflow: hidden;
            max-width: 100%;
            margin: 0 auto;
            margin-left: 30px;
            margin-right: 30px;
        }

        .slider-container .slider {
            display: flex;
            gap: 15px;
            scroll-behavior: smooth;
            overflow-x: auto;
        }

        .slider-container .slider .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            flex: 0 0 auto;
            width: 250px;
            transition: transform 0.3s ease;
        }

        .slider-container .slider .card:hover {
            transform: scale(1.05);
        }

        .card img {
            width: 100%;
            height: 200px;
            display: block;
    transition: opacity 0.3s ease;
        }

        .card-content {
            padding: 15px;
        }

        .card:hover {
    transform: scale(1.05); /* Slightly enlarge the card */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Add shadow */
}

.card:hover::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Dark overlay */
    transition: opacity 0.3s ease;
}

        .card-content h4 {
            margin: 0 0 10px;
            font-size: 1.2em;
            color: #333;
        }

        .card-content p {
            margin: 0;
            color: #555;
        }

        .card-content span {
            display: block;
            margin-bottom: 10px;
            color: #777;
        }

        .card-content .btn {
            position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 10; /* Ensure button is above the overlay */
        }

        .card-content .btn:hover {
            background-color: #1b6b1b;
        }

        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.5em;
            z-index: 1;
        }

        .slider-btn.left {
            left: 10px;
        }

        .slider-btn.right {
            right: 10px;
        }

  
            .card {

                width: 200px;
                height:600px;
            }
        
    </style>
   
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

        <form action="dashboard.php" method="post">
            <div class="form-group">
                <input type="text" name="micro_search" value="<?= htmlspecialchars($search_query) ?>" placeholder="Search Microgreen Title">
            </div>
            <div class="form-group">
                <select name="catid">
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
                <input type="submit" name="btnSearch" value="Search">
            </div>
        </form>

        <div class="slider-container">
            <button class="slider-btn left" id="slideLeft"><</button>
            <div class="slider" id="slider">
                <?php
                $sql = "SELECT micro.*, categories.catname
                        FROM micro
                        INNER JOIN categories ON categories.catid = micro.catid
                        WHERE micro.title LIKE '%$search_query%' AND (micro.catid = '$selected_catid' OR '$selected_catid' = '')
                        ORDER BY micro.microid DESC";
                $res = mysqli_query($con, $sql);
                if ($res && mysqli_num_rows($res) > 0) {
                    while ($data = mysqli_fetch_array($res)) {
                        $max_quantity = $data['available_stock'];
                        $discount = $data['discount'];
                ?>
                        <div class="card">
                            <img src="../admin/uploads/images/<?= htmlspecialchars($data['image']) ?>" alt="<?= htmlspecialchars($data['title']) ?>">
                            <div class="card-content">
                                <h4><?= htmlspecialchars($data['title']) ?></h4>
                                <p><?= htmlspecialchars($data['description']) ?></p>
                                <span><?= htmlspecialchars($data['catname']) ?></span>
                                <p>Price: <?= htmlspecialchars($data['price']) ?></p>
                                <?php if ($discount > 0): ?>
                                    <p>Discount: <?= htmlspecialchars($discount) ?>%</p>
                                <?php endif; ?>
                                <p>Available Stock: <?= htmlspecialchars($max_quantity) ?></p>
                                <a href="../admin/uploads/videos/<?= htmlspecialchars($data['video']) ?>" target="_blank" class="btn">Watch Video</a>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p>No microgreens found</p>";
                }
                ?>
            </div>
            <button class="slider-btn right" id="slideRight">></button>
        </div>
    </section>

    <?php include('footer.php'); ?>
    <?php include('weather.php');?>
    <?php include('social.php') ?>

    <script>
        document.getElementById('slideLeft').onclick = function() {
            document.getElementById('slider').scrollLeft -= 300;
        };
        document.getElementById('slideRight').onclick = function() {
            document.getElementById('slider').scrollLeft += 300;
        };
    </script>
</body>
</html>
