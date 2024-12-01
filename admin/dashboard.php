<?php 
include('../connect.php');

if(!isset($_SESSION['uid'])){
    echo "<script> window.location.href='../login.php';  </script>";
    exit;
}

// Fetch quantity data
$sql = "SELECT title, quantity FROM micro";
$result = mysqli_query($con, $sql);

$titles = [];
$quantities = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $titles[] = $row['title'];
        $quantities[] = $row['quantity'];
    }
}

$titles = json_encode($titles);
$quantities = json_encode($quantities);


// Fetch microgreen sales data
$sales_sql = "
    SELECT microid, SUM(quantity) AS total_quantity
    FROM payments
    GROUP BY microid";
$sales_result = $con->query($sales_sql);

if ($sales_result === false) {
    die("Error: " . $con->error);
}



// Fetch data into an associative array
$sales_data = $sales_result->fetch_all(MYSQLI_ASSOC);

// Fetch data for each section
$category_sql = "SELECT COUNT(catid) AS 'category' FROM `categories`";
$category_res = mysqli_query($con, $category_sql);
$catdata = mysqli_fetch_array($category_res);

$micro_sql = "SELECT COUNT(microid) AS 'total_microgreens' FROM `micro`";
$micro_res = mysqli_query($con, $micro_sql);
$microdata = mysqli_fetch_array($micro_res);

$blog_sql = "SELECT COUNT(blogid) AS 'total_blogs' FROM `blogs`";
$blog_res = mysqli_query($con, $blog_sql);
$blogdata = mysqli_fetch_array($blog_res);

$payment_sql = "SELECT SUM(amount) AS 'total_sales' FROM `payments`";
$payment_res = mysqli_query($con, $payment_sql);
$paymentdata = mysqli_fetch_array($payment_res);

$user_sql = "SELECT COUNT(userid) AS 'total_users' FROM `users`";
$user_res = mysqli_query($con, $user_sql);
$userdata = mysqli_fetch_array($user_res);

$ads_sql = "SELECT COUNT(id) AS 'total_ads' FROM `ads`";
$ads_res = mysqli_query($con, $ads_sql);
$adsdata = mysqli_fetch_array($ads_res);

$feedback_sql = "SELECT COUNT(feedback_id) AS 'total_feedback' FROM `feedback`";
$feedback_res = mysqli_query($con, $feedback_sql);
$feedbackdata = mysqli_fetch_array($feedback_res);

// Fetch sales by category data for the pie chart
$sales_by_category_sql = "
    SELECT c.catname AS category, SUM(p.amount) AS total_sales 
    FROM payments p
    JOIN micro m ON p.microid = m.microid
    JOIN categories c ON m.catid = c.catid
    GROUP BY c.catid";
$sales_by_category_res = mysqli_query($con, $sales_by_category_sql);

$categories = [];
$category_sales = [];

while ($row = mysqli_fetch_assoc($sales_by_category_res)) {
    $categories[] = $row['category'];
    $category_sales[] = $row['total_sales'];
}

$categories = json_encode($categories);
$category_sales = json_encode($category_sales);

// Fetch recent activities (e.g., recent orders)
$recent_sql = "SELECT user_id, microid, quantity, created_at FROM `payments` ORDER BY created_at DESC LIMIT 20";
$recent_res = mysqli_query($con, $recent_sql);

$recent_activities = [];
while ($row = mysqli_fetch_assoc($recent_res)) {
    $recent_activities[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../admin/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Admin Dashboard</title>
    <style>
        .container-admin {
            padding: 20px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Added gap between cards */
        }

        .card-view {
            flex: 1 1 calc(33.333% - 20px); /* Three cards per row with spacing */
            box-sizing: border-box;
            margin-bottom: 20px; /* Space below each card */
        }

        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            height: 100%; /* Make cards fill the container height */
        }

        .card-body {
            padding: 15px;
        }

        .card-text h5 {
            font-size: 1.2em;
            margin-bottom: 10px;
            color: #333; /* Darker color for better visibility */
        }

        .card-text h6 {
            font-size: 1.5em;
            color: #4CAF50; /* Green color */
        }

        .chart-container {
            width: 60%;
            margin: auto;
            margin-top: 30px;
        }

        .sales-h1 {
            text-align: center;
            color: #4CAF50; /* Green color */
            font-size: 2em;
            margin-top: 40px;
            margin-bottom: 20px;
        }

        .status-cards {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .status-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
            width: 30%;
        }

        .recent-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .recent-table th, .recent-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .recent-table th {
            background-color: #4CAF50; /* Green color */
            color: #fff;
        }
    </style>
</head>
<body>

<?php include('header.php') ?>


<div class="container-admin">
    <h4>Welcome to Admin Dashboard</h4>

    <div class="row">
        <!-- Categories Card -->
        <div class="card-view">
            <div class="card">
                <div class="card-body">
                    <div class="card-text">
                        <h5>CATEGORIES</h5>
                        <h6><?=$catdata['category']?></h6>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Blogs Card -->
        <div class="card-view">
            <div class="card">
                <div class="card-body">
                    <div class="card-text">
                        <h5>BLOGS</h5>
                        <h6><?=$blogdata['total_blogs']?></h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Card -->
        <div class="card-view">
            <div class="card">
                <div class="card-body">
                    <div class="card-text">
                        <h5>USERS</h5>
                        <h6><?=$userdata['total_users']?></h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ads Card -->
        <div class="card-view">
            <div class="card">
                <div class="card-body">
                    <div class="card-text">
                        <h5>ADS</h5>
                        <h6><?=$adsdata['total_ads']?></h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback Card -->
        <div class="card-view">
            <div class="card">
                <div class="card-body">
                    <div class="card-text">
                        <h5>FEEDBACK</h5>
                        <h6><?=$feedbackdata['total_feedback']?></h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Sales Card -->
        <div class="card-view">
            <div class="card">
                <div class="card-body">
                    <div class="card-text">
                        <h5>TOTAL SALES</h5>
                        <h6>$<?=$paymentdata['total_sales']?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-view">
            <div class="card">
                <div class="card-body">
                    <div class="card-text">
                        <h5>WEATHER</h5>
                        <h6>$<?=$paymentdata['total_sales']?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <h1 class="sales-h1">Data Analysis</h1>
    <div class="chart-container">
        <canvas id="dataChart"></canvas>
    </div>
    <div class="chart-container">
        <canvas id="categorySalesChart"></canvas>
    </div>

    <!-- System Status -->
    <h1 class="sales-h1">System Status</h1>
    <div class="status-cards">
        <div class="status-card">
            <h5>Server Status</h5>
            <p>Online</p>
        </div>
        <div class="status-card">
            <h5>Database Status</h5>
            <p><?php echo mysqli_ping($con) ? 'Connected' : 'Disconnected'; ?></p>
        </div>
        <div class="status-card">
            <h5>PHP Version</h5>
            <p><?php echo phpversion(); ?></p>
        </div>
    </div>

   

<script>
// Data Chart (excluding Payments)
const dataCtx = document.getElementById('dataChart').getContext('2d');
const dataChart = new Chart(dataCtx, {
    type: 'bar',
    data: {
        labels: ['Categories', 'Microgreens', 'Blogs', 'Users', 'Ads', 'Feedback'],
        datasets: [{
            label: 'Data',
            data: [
                <?=$catdata['category']?>,
                <?=$microdata['total_microgreens']?>,
                <?=$blogdata['total_blogs']?>,
                <?=$userdata['total_users']?>,
                <?=$adsdata['total_ads']?>,
                <?=$feedbackdata['total_feedback']?>
            ],
            backgroundColor: 'rgba(43, 166, 53, 0.2)',
            borderColor: 'rgba(43, 166, 53, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            x: {
                grid: {
                    color: 'rgba(255, 255, 255, 0.2)',
                },
                ticks: {
                    color: 'rgba(255, 255, 255, 1)'
                }
            },
            y: {
                grid: {
                    color: 'rgba(255, 255, 255, 0.2)'
                },
                ticks: {
                    color: 'rgba(255, 255, 255, 1)'
                },
                beginAtZero: true
            }
        }
    }
});

// Sales by Category Chart
const categoryCtx = document.getElementById('categorySalesChart').getContext('2d');
const categorySalesChart = new Chart(categoryCtx, {
    type: 'pie',
    data: {
        labels: <?=$categories?>,
        datasets: [{
            label: 'Sales by Category',
            data: <?=$category_sales?>,
            backgroundColor: [
                'rgba(0, 128, 0, 0.2)',  // Green
                'rgba(255, 255, 255, 0.2)',  // White
                'rgba(0, 128, 0, 0.2)',  // Green
                'rgba(255, 255, 255, 0.2)',  // White
                'rgba(0, 128, 0, 0.2)',  // Green
                'rgba(255, 255, 255, 0.2)'   // White
            ],
            borderColor: [
                'rgba(0, 128, 0, 1)',  // Green
                'rgba(255, 255, 255, 1)',  // White
                'rgba(0, 128, 0, 1)',  // Green
                'rgba(255, 255, 255, 1)',  // White
                'rgba(0, 128, 0, 1)',  // Green
                'rgba(255, 255, 255, 1)'   // White
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Sales Distribution by Category'
            }
        },
        // Adjust the size of the chart
        aspectRatio: 1, // You can change this to control the aspect ratio
        maintainAspectRatio: false, // Set to true if you want to maintain the aspect ratio
        layout: {
            padding: 10  // Adjust padding to make the chart smaller
        }
    }
});

</script>

<h1>Microgreens Quantity Dashboard</h1>

<div style="width: 80%; margin: 0 auto;">
    <canvas id="quantityChart"></canvas>
</div>

<script>
    var ctx = document.getElementById('quantityChart').getContext('2d');
    var quantityChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= $titles ?>,
            datasets: [{
                label: 'Quantity',
                data: <?= $quantities ?>,
                backgroundColor: '#4CAF50'
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
 <!-- Recent Activities -->
 <h1 class="sales-h1">Recent Activities</h1>
    <table class="recent-table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Microgreen ID</th>
                <th>Quantity</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recent_activities as $activity): ?>
                <tr>
                    <td><?= $activity['user_id'] ?></td>
                    <td><?= $activity['microid'] ?></td>
                    <td><?= $activity['quantity'] ?></td>
                    <td><?= $activity['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php include('weather.php');?>
</div>
</body>
</html>
