<?php
// signup.php - Handle sign-up functionality

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Password validation
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the email already exists in the database
        $sql = "SELECT * FROM user WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $error_message = "An account with this email already exists.";
        } else {
            // Insert new user into the database
            $sql = "INSERT INTO user (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$hashed_password')";
            if ($conn->query($sql) === TRUE) {
                // Redirect to login page after successful signup
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Error: " . $conn->error;
            }
        }
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Sign Up - CampusFixIt</title>
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

        .signup-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
            margin-top: 60px;
        }

        .signup-card {
            background: rgba(26, 26, 46, 0.95);
            padding: 2rem;
            border-radius: 10px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .signup-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .signup-header h1 {
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

        .signup-footer {
            text-align: center;
            margin-top: 1.5rem;
        }

        .signup-footer a {
            color: #7f5af0;
            text-decoration: none;
        }

        .signup-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body style="background: url('../../assets/images/background/nature-7047433_1280.jpg') no-repeat center center fixed; background-size: cover;">
    <nav>
        <a href="../index.html" class="logo">CampusFixIt</a>
        <div class="nav-links">
            <a href="login.php">Login</a>
        </div>
    </nav>

    <div class="signup-container">
        <div class="signup-card">
            <div class="signup-header">
                <h1>Create an Account</h1>
                <p>Sign up to join CampusFixIt</p>
            </div>

            <?php if (isset($error_message)) { ?>
                <div style="color: red; text-align: center; margin-bottom: 10px;">
                    <strong><?php echo $error_message; ?></strong>
                </div>
            <?php } ?>

            <form action="signup.php" method="POST">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn">Sign Up</button>
            </form>

            <div class="signup-footer">
                <p><a href="login.php">Already have an account? Log in</a></p>
            </div>
        </div>
    </div>
</body>
</html>
