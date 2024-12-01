<style>
/* Import Google Font */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
}

#footer {
    background-color: #0F2429;
    color: #ecf0f1;
    padding: 30px 0;
}

.footer-details {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
}


.nav-footer a {
    color: #ecf0f1;
    text-decoration: none;
    margin: 0 15px;
    font-weight: 500;
    transition: color 0.3s;
}

.nav-footer a:hover {
    color:  #32cd32;
}

.nav-footer .active {
    color: #f39c12;
}

.copyright {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
    color: #bdc3c7;
}

  
</style>

<footer id="footer">
    <div class="container">
        <div class="footer-details">
            <div class="nav-footer">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
                <a class="nav-link" href="category.php">Categories</a>
                <a class="nav-link" href="details.php">Details</a>
                <a class="nav-link" href="add_blog.php">Blogs</a>
                <a class="nav-link" href="hotel.php">Hotel</a>
                <a class="nav-link" href="feedback.php">feedback</a>
                <a class="nav-link" href="help.php">Help</a>
            </div>
        </div>

        <div class="copyright">
            <?php $year = date('Y'); ?>
            © Copyright <?= $year ?>. All rights reserved. <strong><span>Microgreens</span></strong>
        </div>
    </div>
</footer>
