<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // Your database username
$password = "yEnma123@"; // Your database password
$dbname = "campusfixit"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve login credentials
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    /// Check if email exists in the users table
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role']; 

        // Debug: Print role before redirection
        echo "User Role: " . $user['role'] . "<br>";
        echo "Redirecting...";

        // Verify role and redirection
        if ($user['role'] === 'admin') {
            $admin_dashboard_path = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/admin_dashboard.php";
            
            header("Location: " . $admin_dashboard_path);
            exit();
        } elseif ($user['role'] === 'user') {
            $user_dashboard_path = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/user_dashboard.php";
            
            header("Location: " . $user_dashboard_path);
            exit();
        } else {
            // More detailed error handling
            $error_message = "Invalid user role: " . $user['role'];
        }
    } else {
        $error_message = "Invalid password. Please try again.";
    }
} else {
    $error_message = "No account found with this email. Please sign up.";
}

}
// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Login - CampusFixIt</title>
    <style>
        * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            body {
                background-size: cover;
                color: #e1e1e1;
                min-height: 100vh;
                font-size: 16px;
                line-height: 1.5;
                display: flex;
                flex-direction: column;
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

            .login-container {
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                padding: 2rem;
                margin-top: 60px;
            }

            .login-card {
                background: rgba(26, 26, 46, 0.95);
                padding: 2rem;
                border-radius: 10px;
                width: 100%;
                max-width: 500px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .login-header {
                text-align: center;
                margin-bottom: 2rem;
            }

            .login-header h1 {
                color: #7f5af0;
                font-size: 2rem;
                margin-bottom: 0.5rem;
            }

            .form-group {
                margin-bottom: 1.5rem;
            }

            .form-group label {
                display: block;
                margin-bottom: 0.5rem;
                color: #e1e1e1;
            }

            .form-group input {
                width: 100%;
                padding: 0.8rem;
                border: 1px solid #94a1b2;
                border-radius: 4px;
                background: rgba(255, 255, 255, 0.1);
                color: #e1e1e1;
                font-size: 1rem;
            }

            .form-group input:focus {
                outline: none;
                border-color: #7f5af0;
            }

            .btn {
                width: 100%;
                padding: 0.8rem;
                border: none;
                border-radius: 4px;
                background: #7f5af0;
                color: #fff;
                font-size: 1rem;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .btn:hover {
                background: #6b47d6;
            }

            .login-footer {
                text-align: center;
                margin-top: 1.5rem;
            }

            .login-footer a {
                color: #7f5af0;
                text-decoration: none;
            }

            .login-footer a:hover {
                text-decoration: underline;
            }
    </style>
</head>
<body style="background: url('../../assets/images/background/nature-7047433_1280.jpg') no-repeat center center fixed;">
    <nav>
        <a href="../index.html" class="logo">CampusFixIt</a>
        <div class="nav-links">
            <a href="signup.php">Sign Up</a>
        </div>
    </nav>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Welcome Back</h1>
                <p>Login to continue</p>
            </div>
            <?php if (!empty($error_message)) { ?>
                <div style="color: red; text-align: center; margin-bottom: 10px;">
                    <strong><?php echo $error_message; ?></strong>
                </div>
            <?php } ?>
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            <div class="login-footer">
                <p><a href="signup.php">Don't have an account? Sign up</a></p>
            </div>
        </div>
    </div>
</body>
</html>