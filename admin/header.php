<style>
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.container {
  display: flex;
  align-items: center;
}

#menu-icon {
  font-size: 30px;
  cursor: pointer;
  color: white;
  padding: 15px;
  background-color: #333; /* Dark background for contrast */
  border-radius: 5px;
  margin: 15px;
  transition: background-color 0.3s ease; /* Smooth background color change */
}

#menu-icon:hover {
  background-color: #4CAF50; /* Green hover effect */
}

.sidebar {
  width: 0;
  height: 100vh;
  background-color: #333; /* Dark background */
  overflow-y: auto;
  overflow-x: hidden;
  position: fixed;
  top: 0;
  left: 0;
  transition: width 0.3s ease; /* Smooth opening/closing */
  z-index: 1000;
}

.sidebar ul {
  list-style-type: none;
  padding: 0;
  margin: 0;
}

.sidebar li {
  margin: 15px 0;
}

.sidebar .nav-link {
  color: white;
  text-decoration: none;
  padding: 10px 20px;
  display: block;
  font-size: 16px;
  border-left: 3px solid transparent;
  transition: background-color 0.3s ease, border-left-color 0.3s ease; /* Smooth hover effects */
}

.sidebar .nav-link:hover {
  background-color: #4CAF50; /* Green background on hover */
  color: white;
  border-left-color: #fff; /* White border on hover */
  border-radius: 0 25px 25px 0; /* Rounded corners on hover */
}

.sidebar .nav-link.active {
  background-color: #4CAF50; /* Active link background */
  border-left-color: #fff; /* White border for active link */
  border-radius: 0 25px 25px 0; /* Rounded corners for active link */
}

#overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 900;
}


</style>

<header id="header">
  <div class="container">
    <!-- Menu icon -->
    <span id="menu-icon" onclick="toggleSidebar()">☰</span>

    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar">
      <ul>
        <li><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li><a class="nav-link" href="categories.php">Categories</a></li>
        <li><a class="nav-link" href="add_product.php">Add Product</a></li>
        <li><a class="nav-link" href="addStock.php">Add Stock</a>
        <li><a class="nav-link" href="add_blog.php">Blogs</a></li>
        <li><a class="nav-link" href="payments.php">Payments</a></li>
        <li><a class="nav-link" href="viewallusers.php">Users</a></li>
        <li><a class="nav-link" href="view_framer.php">Farmers</a></li>
        <li><a class="nav-link" href="ads.php">Ads</a></li>
        <li><a class="nav-link" href="staff_register.php">Staff Registration</a></li>
        <li><a class="nav-link" href="sellers_micro.php">Sellers Micro</a></li>
        <li><a class="nav-link" href="view_feedback.php">Feedbacks</a></li>
        <li><a class="nav-link" href="payselers.php">Pay selers</a></li>
        <li><a class="nav-link" href="hotel.php">Add hotel</a></li>
        <li><a class="nav-link" href="complaints.php">View complain</a></li>
        <li><a class="nav-link" href="help.php">Crop Management</a></li>
        <li><a class="nav-link" href="Rules.php">Rules and Regulations</a></li>
        <li><a class="nav-link" href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </div>
</header>

<!-- Overlay -->
<div id="overlay" onclick="closeSidebar()"></div>


<script>
function toggleSidebar() {
  var sidebar = document.getElementById("sidebar");
  var overlay = document.getElementById("overlay");
  
  if (sidebar.style.width === "250px") {
    closeSidebar();
  } else {
    sidebar.style.width = "250px";
    overlay.style.display = "block"; // Show the overlay when sidebar is open
  }
}

function closeSidebar() {
  var sidebar = document.getElementById("sidebar");
  var overlay = document.getElementById("overlay");
  
  sidebar.style.width = "0";
  overlay.style.display = "none"; // Hide the overlay when sidebar is closed
}

// Close sidebar if clicked outside of it
document.getElementById("overlay").addEventListener("click", closeSidebar);
</script>

