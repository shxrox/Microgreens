<style>
body {
    font-family: 'Lato', sans-serif;
    background-color: #f1f5f9;
    color: #333;
    margin: 0;
    padding: 0;
    font-size: 1rem;
}


.product-page {
    display: flex;
    flex-wrap: wrap; /* Allows items to wrap to the next line if they exceed container width */
    gap: 20px; /* Adds space between items */
}

.product-detail {
    display: flex;
    flex-wrap: wrap; /* Makes the layout flexible for smaller screens */
    gap: 20px; /* Adds some space between the media and details sections */
}

.media-container {
    display: flex; /* Ensures video and image are side by side */
    gap: 20px; /* Adds space between the video and image */
}

.video-container, .image-container {
    max-width: 300px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.video-container video, .image-container img {
    width: 100%;
    height: auto; /* Maintain aspect ratio */
}

.details-container h1 {
    color: #333;
    font-size: 2em;
    font-weight: 700;
    margin: 0;
}

.details-container p {
    color: #666;
    line-height: 1.6;
    margin: 10px 0;
}

.price {
    color: #e60023;
    font-size: 1.5em;
    font-weight: 700;
}

.buy-options {
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.quantity-control {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.quantity-control button {
    background-color:#0f2429;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.quantity-control button:hover {
    background-color:  #1A3942;;
            color:#32CD32;
           
   
    transform: translateY(-2px);
}

.quantity-control input {
    width: 60px;
    text-align: center;
    font-size: 1.2em;
    margin: 0 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

.buy, .cart-btn {
    width: 100%;
    max-width: 300px;
    padding: 15px;
    font-size: 1.2em;
    font-weight: 700;
    text-align: center;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    margin: 10px 0;
    transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
}

.buy {
    background-color: #0f2429;
    color: white;
}

.buy:hover {
    background-color:#1A3942;
            color:#32CD32;
            
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.cart-btn {
    background-color: #0f2429;
    color: white;
}

.cart-btn:hover {
    background-color:#1A3942;
            color:#32CD32;
            
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.message {
    display: none;
    padding: 15px;
    margin: 20px 0;
    border-radius: 8px;
    background-color: #28a745;
    color: #fff;
    font-size: 1.2em;
    text-align: center;
    opacity: 1;
}

.message.error {
    background-color: #dc3545;
}

</style>

<?php
include 'connect.php'; 
include('header.php');

if (isset($_GET['microid'])) {
    $microid = $_GET['microid'];
    $sql = "SELECT micro.*, categories.catname
            FROM micro
            INNER JOIN categories ON categories.catid = micro.catid
            WHERE micro.microid = '$microid'";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        $data = mysqli_fetch_array($res);
        $discount = $data['discount'];
        $image = htmlspecialchars($data['image']);
        $video = htmlspecialchars($data['video']);
        $title = htmlspecialchars($data['title']);
        $catname = htmlspecialchars($data['catname']);
        $description = htmlspecialchars($data['description']);
        $price = htmlspecialchars($data['price']);
        $max_quantity = $data['available_stock'];
        $manufacture_date = $data['manufacture_date'];
        $expire_date = $data['expire_date'];
        
    } else {
        echo "<p>Product not found</p>";
        exit();
    }
} else {
    echo "<p>No product ID specified</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional: External CSS file -->
</head>
<body>
    <header>
        <!-- Navigation or Branding here -->
    </header>
    <main>
        <div class="product-detail">
            <div class="video-container">
                <video controls autoplay>
                    <source src="./admin/uploads/videos/<?= $video ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            
            <div class="image-container">
                <img src="./admin/uploads/images/<?= $image ?>" alt="<?= $title ?>">
                </div>
            </div>
            <div class="details-container">
    <h1><?= $title ?></h1>
    <span>Category: <?= $catname ?></span>
    <p><?= $description ?>                                                         <br>
                                                                                   <br>
                                                                                   <br></p>
    <p>Price: <?= $price ?></p>
    <p>Available Stock: <?= $max_quantity ?></p>
    <p>Manufacture Date: <?= $manufacture_date ?></p>
    <p>Expire Date: <?= $expire_date ?></p>
    
    <div class="buy-options">
        <!-- Buy Now Form -->
        <form action="payment.php" method="post">
            <input type="hidden" id="microid" name="microid" value="<?= $microid ?>">
            <input type="hidden" id="hidden-quantity" name="quantity" value="1">
            
            <div class="quantity-control">
                <button type="button" onclick="changeQuantity(-1, 'quantity', <?= $max_quantity ?>)">-</button>
                <input type="text" id="quantity" value="1" readonly>
                <button type="button" onclick="changeQuantity(1, 'quantity', <?= $max_quantity ?>)">+</button>
            </div>
            
            <input type="submit" name="buy" value="Buy Now" class="buy">
        </form>

        <!-- Add to Cart Form -->
        <form id="add-to-cart-form" onsubmit="return false;">
            <input type="hidden" name="microid" value="<?= $microid ?>">
            <input type="hidden" id="cart-quantity" name="quantity" value="1">
            <button type="button" id="add-to-cart-btn" class="cart-btn">Add to Cart</button>
        </form>

        <div id="message" class="message"></div>
    </div>
</div>
        </div>
    </main>
    <script>
        function changeQuantity(amount, inputId, maxQuantity) {
    var quantityInput = document.getElementById(inputId);
    var currentValue = parseInt(quantityInput.value);
    var newValue = currentValue + amount;

    if (newValue >= 1 && newValue <= maxQuantity) {
        quantityInput.value = newValue;
        document.getElementById('hidden-' + inputId).value = newValue;
    }
}


        document.getElementById('add-to-cart-btn').addEventListener('click', function() {
            var form = document.getElementById('add-to-cart-form');
            var formData = new FormData(form);

            fetch('addtocart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                var messageElement = document.getElementById('message');
                messageElement.textContent = 'Product has been added to the cart!';
                messageElement.className = 'message';
                messageElement.style.display = 'block';
            })
            .catch(error => {
                var messageElement = document.getElementById('message');
                messageElement.textContent = 'An error occurred. Please try again.';
                messageElement.className = 'message error';
                messageElement.style.display = 'block';
            });
        });
    </script>
</body>
<?php include('footer.php') ?>
<?php include('chat.php') ?>
<?php include('social.php') ?>

</html>
