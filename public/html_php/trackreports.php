<?php
// Database configuration
define('DB_SERVER', 'localhost');   // MySQL server (typically localhost)
define('DB_USERNAME', 'root');     // Replace with your actual database username
define('DB_PASSWORD', 'yEnma123@'); // Replace with your actual database password
define('DB_DATABASE', 'campusfixit'); // Replace with your actual database name

// Create a database connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$status = null;
$progress = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reportId'])) {
    $reportId = trim($_POST['reportId']);

    // Check if table maintenance_reports exists
    $checkTable = $conn->query("SHOW TABLES LIKE 'maintenance_reports'");
    if ($checkTable->num_rows == 0) {
        die("The table 'maintenance_reports' does not exist. Please create the table and try again.");
    }

    // Prepare the query to retrieve the report data
    $sql = "SELECT * FROM maintenance_reports WHERE report_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $reportId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a report with the given ID exists
        $report = $result->fetch_assoc();
        if ($report) {
            $status = $report['status'];
            $progress = $report['progress'];
        } else {
            $status = "Report not found.";
            $progress = "0%";
        }

        $stmt->close();
    } else {
        die("Error preparing SQL query: " . $conn->error);
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Track Report Progress - CampusFixIt</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f0f0f0;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background: rgba(26, 26, 46, 0.95);
            padding: 1rem 2rem;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: #7f5af0;
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-links {
            list-style-type: none;
            display: flex;
        }

        .nav-links li {
            margin-left: 2rem;
        }

        .nav-links a {
            color: #e1e1e1;
            text-decoration: none;
            font-size: 1.1rem;
        }

        .nav-links a:hover {
            color: #7f5af0;
        }

        main {
            flex: 1;
            margin-top: 80px;
            padding: 3rem 1rem;
            text-align: center;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: 2rem;
            border-radius: 10px;
        }

        h2 {
            color: #7f5af0;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        input {
            padding: 0.75rem;
            font-size: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            width: 250px;
            border: 2px solid #7f5af0;
            color: #333;
            text-align: center;
        }

        button {
            padding: 0.5rem 1.5rem;
            background-color: #7f5af0;
            color: white;
            font-size: 1.2rem;
            border: none;
            border-radius: 8px;
            margin-top: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #5a2dba;
        }

        .progress-bar {
            margin-top: 2rem;
            width: 100%;
            height: 20px;
            border-radius: 10px;
            background-color: #e1e1e1;
            overflow: hidden;
            display: <?php echo isset($progress) && $progress != "0%" ? 'block' : 'none'; ?>;
        }

        .progress-fill {
            height: 100%;
            width: <?php echo isset($progress) ? $progress : '0%'; ?>;
            background-color: #7f5af0;
            transition: width 1s;
        }

        footer {
            background: rgba(26, 26, 46, 0.95);
            color: #e1e1e1;
            padding: 1.5rem 0;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        footer a {
            color: #e1e1e1;
            text-decoration: none;
            margin-left: 1rem;
        }

        footer a:hover {
            color: #7f5af0;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <h1 class="logo">CampusFixIt</h1>
            <ul class="nav-links">
                <li><a href="user_dashboard.php">Home</a></li>
                <li><a href="submit_report.php">Submit Report</a></li>
                <li><a href="login.php">Admin</a></li>
                <li><a href="login.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Track Report Progress</h2>
        <form action="trackreports.php" method="POST">
            <input type="text" name="reportId" placeholder="Enter Report ID" required>
            <button type="submit">Check Status</button>
        </form>

        <?php if (isset($status)): ?>
            <div>
                <p>Status: <?php echo htmlspecialchars($status); ?></p>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 CampusFixIt. All rights reserved.</p>
    </footer>
</body>
</html>
