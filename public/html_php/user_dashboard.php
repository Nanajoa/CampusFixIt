  
<?php
// Start session to access user data
session_start();

// Assuming first name is stored in the session
$firstName = $_SESSION['first_name'] ?? 'User'; // Default to 'User' if not logged in

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../html_php/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>CampusFixIt - User Dashboard</title>
    <style>
        <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url('../../assets/images/background/nature-7047433_1280.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #e1e1e1;
            min-height: 100vh;
            font-size: 16px;
            line-height: 1.5;
        }

        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(26, 26, 46, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .nav-links {
            display: flex;
            align-items: center;
        }

        .nav-links a {
            color: #e1e1e1;
            text-decoration: none;
            margin-left: 2rem;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #7f5af0;
        }

        .logo {
            color: #7f5af0;
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
        }

        .dashboard-content {
            max-width: 1200px;
            margin: 120px auto 50px;
            padding: 0 20px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .dashboard-card {
            background: rgba(26, 26, 46, 0.9);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .dashboard-card:hover {
            transform: scale(1.05);
        }

        .dashboard-card i {
            font-size: 3rem;
            color: #7f5af0;
            margin-bottom: 1rem;
        }

        .dashboard-card h2 {
            color: #e1e1e1;
            margin-bottom: 1rem;
        }

        .dashboard-card p {
            color: #94a1b2;
            margin-bottom: 1.5rem;
        }

        .dashboard-card a {
            display: inline-block;
            background: #280e74;
            color: #fffffe;
            padding: 0.8rem 2rem;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .dashboard-card a:hover {
            background: #6b47d6;
        }

        footer {
            background: rgba(26, 26, 46, 0.95);
            color: #e1e1e1;
            padding: 3rem 0;
            position: relative;
            margin-top: 2rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            padding: 0 2rem;
        }

        .footer-section {
            flex: 1;
            margin: 0 1rem;
        }

        .footer-section h4 {
            color: #7f5af0;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .footer-links a {
            color: #e1e1e1;
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #7f5af0;
        }

        .footer-contact {
            font-size: 0.9rem;
            color: #94a1b2;
        }

        .footer-bottom {
            background: rgba(26, 26, 46, 0.9);
            padding: 1rem 0;
            text-align: center;
            margin-top: 2rem;
        }

        .scroll-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #7f5af0;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 50%;
            font-size: 20px;
            display: none;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .scroll-to-top.show {
            display: block;
        }
    </style>
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <a href="user_dashboard.php" class="logo">CampusFixIt</a>
        <div class="nav-links">
            <span>Welcome, <?php echo htmlspecialchars($firstName); ?>!</span>
            <a href="../html_php/logout.php">Log Out</a>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="dashboard-content">
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <i class="fas fa-file-upload"></i>
                <h2>Submit Report</h2>
                <p>Have an issue on campus? Submit a new report here.</p>
                <a href="submit_report.php">Create Report</a>
            </div>
            <div class="dashboard-card">
                <i class="fas fa-clipboard-list"></i>
                <h2>Track Reports</h2>
                <p>View the status of your submitted reports.</p>
                <a href="trackreports.php">View Reports</a>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>CampusFixIt</h4>
                <p>Empowering students to solve campus challenges efficiently and collaboratively.</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <div class="footer-links">
                    <a href="../html_php/about.html">About Us</a>
                </div>
            </div>
            <div class="footer-section">
                <h4>Contact</h4>
                <div class="footer-contact">
                    <p>Email: supportcentre@ashesi.edu.gh</p>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 CampusFixIt. All rights reserved.</p>
        </div>
    </footer>

    <!-- Scroll-to-Top Button -->
    <button class="scroll-to-top" id="scrollToTopBtn">
        <i class="fa fa-arrow-up"></i>
    </button>

    <!-- JavaScript -->
    <script>
        const scrollToTopBtn = document.getElementById('scrollToTopBtn');
        window.onscroll = function () {
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                scrollToTopBtn.classList.add('show');
            } else {
                scrollToTopBtn.classList.remove('show');
            }
        };

        scrollToTopBtn.onclick = function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };
    </script>
</body>
</html>
