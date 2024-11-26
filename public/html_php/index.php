<?php
// index.php - Main Entry Point with PHP logic

// Determine the page to load
if (isset($_GET['view'])) {
    $view = $_GET['view'];
} else {
    $view = 'home';  // Default view
}

switch ($view) {
    case 'login':
        include 'login.php';
        break;
    case 'signup':
        include 'signup.php';
        break;
    default:
        // Default home page view
        echo '<!DOCTYPE html>';
        echo '<html lang="en">';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">';
        echo '<title>CampusFixIt</title>';
        echo '<style>';
        // Add custom CSS here
        echo '* { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; }';
        echo 'body { background: url("../../assets/images/background/nature-7047433_1280.jpg") no-repeat center center fixed; background-size: cover; color: #e1e1e1; font-size: 16px; line-height: 1.5; }';
        echo 'nav { position: fixed; top: 0; left: 0; width: 100%; background: rgba(26, 26, 46, 0.95); backdrop-filter: blur(10px); padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); z-index: 1000; }';
        echo '.logo { color: #7f5af0; font-size: 1.5rem; font-weight: bold; text-decoration: none; }';
        echo '.hero { height: 100vh; display: flex; justify-content: center; align-items: center; text-align: center; padding: 2rem; position: relative; overflow: hidden; }';
        echo '.cta-buttons { display: flex; gap: 1rem; justify-content: center; }';
        echo '.btn { padding: 0.8rem 2rem; border-radius: 8px; font-size: 1rem; text-decoration: none; transition: all 0.3s ease; }';
        echo '.btn-primary { background: #280e74; color: #fffffe; }';
        echo '.btn-secondary { background: transparent; color: #280e74; border: 2px solid #280e74; }';
        echo '.stats-section { background: #280e74; padding: 4rem 2rem; }';
        echo '.footer { background: rgba(26, 26, 46, 0.95); color: #e1e1e1; padding: 2rem 0; position: relative; }';
        echo '.scroll-to-top { position: fixed; bottom: 20px; right: 20px; background-color: #7f5af0; color: white; border: none; padding: 15px; border-radius: 50%; font-size: 20px; display: none; cursor: pointer; transition: opacity 0.3s ease; }';
        echo '.scroll-to-top.show { display: block; }';
        echo '</style>';
        echo '</head>';
        echo '<body>';

        // Navigation bar
        echo '<nav>';
        echo '<a href="index.php" class="logo">CampusFixIt</a>';
        echo '<div class="nav-links">';
        echo '<a href="?view=login">Log In</a>';
        echo '</div>';
        echo '</nav>';

        // Hero section
        echo '<div class="hero">';
        echo '<div class="hero-content">';
        echo '<h1>Welcome to CampusFixIt</h1>';
        echo '<p class="subtitle">Your go-to platform for all campus-related fixes</p>';
        echo '<div class="cta-buttons">';
        echo '<a href="?view=signup" class="btn btn-primary">Get Started</a>';
        echo '<a href="about.html" class="btn btn-secondary">Learn More</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        // Stats section
        echo '<div class="stats-section">';
        echo '<div class="stats-container">';
        echo '<div class="stat-card">';
        echo '<div class="stat-value">500+</div>';
        echo '<div class="stat-label">Students</div>';
        echo '<p class="stat-description">Join a thriving community of students</p>';
        echo '</div>';
        echo '<div class="stat-card">';
        echo '<div class="stat-value">100+</div>';
        echo '<div class="stat-label">Projects Completed</div>';
        echo '<p class="stat-description">Countless projects for students</p>';
        echo '</div>';
        echo '<div class="stat-card">';
        echo '<div class="stat-value">10+</div>';
        echo '<div class="stat-label">Collaborations</div>';
        echo '<p class="stat-description">Join hands with like-minded peers</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        // Footer section
        echo '<footer>';
        echo '<div class="footer-content">';
        echo '<div class="footer-section footer-logo">';
        echo '<a href="index.php">CampusFixIt</a>';
        echo '<p class="footer-description">Helping students get things done!</p>';
        echo '</div>';
        echo '<div class="footer-section">';
        echo '<div class="footer-bottom-links">';
        echo '<a href="about.html">About</a><span class="separator">|</span>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="footer-bottom">';
        echo '<p>&copy; 2024 CampusFixIt. All rights reserved.</p>';
        echo '</div>';
        echo '</footer>';

        // Scroll to top button
        echo '<button class="scroll-to-top" id="scrollToTopBtn">';
        echo '<i class="fa fa-arrow-up"></i>';
        echo '</button>';

        // Scroll to Top Button Functionality (JavaScript)
        echo '<script>';
        echo 'const scrollToTopBtn = document.getElementById("scrollToTopBtn");';
        echo 'window.onscroll = function () {';
        echo 'if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {';
        echo 'scrollToTopBtn.classList.add("show");';
        echo '} else {';
        echo 'scrollToTopBtn.classList.remove("show");';
        echo '}';
        echo '};';
        echo 'scrollToTopBtn.onclick = function () {';
        echo 'window.scrollTo({ top: 0, behavior: "smooth" });';
        echo '};';
        echo '</script>';

        echo '</body>';
        echo '</html>';
        break;
}
?>
