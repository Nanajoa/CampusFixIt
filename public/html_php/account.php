<?php
// Establish a connection to the database
$servername = "localhost";
$username = "root";
$password = "yEnma123@";
$dbname = "campusfixit";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session to get the logged-in user's ID
session_start();
$id = $_SESSION['id']; // Assume 'user_id' is stored in the session

// Query to fetch user details (including first_name, last_name, email, and role)
$sql = "SELECT first_name, last_name, email,role 
        FROM users 
        WHERE id = '$id'";
$result = $conn->query($sql);

// Fetch the user's data from the database
$user = $result->fetch_assoc();

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusFixIt - Account Management</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        /* Your existing CSS styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #7f5af0, #2d2a45);
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .account-container {
            background-color: #1e1e2e;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 500px;
            padding: 2rem;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }

        .profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #7f5af0;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 1rem;
        }

        .profile-pic i {
            font-size: 3rem;
            color: white;
        }

        .user-info h2 {
            margin: 0;
            font-size: 1.5rem;
        }

        .user-info p {
            margin: 0.5rem 0 0;
            color: #ccc;
        }

        .account-section {
            background-color: #2a2a3c;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .account-section h3 {
            margin-top: 0;
            color: #7f5af0;
            border-bottom: 2px solid #7f5af0;
            padding-bottom: 0.5rem;
        }

        .account-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .account-details p {
            margin: 0.5rem 0;
        }

        .edit-btn {
            background: #7f5af0;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .edit-btn:hover {
            background: #6a47d3;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
        }

        .action-btn {
            background: transparent;
            border: 2px solid #7f5af0;
            color: #7f5af0;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: #7f5af0;
            color: white;
        }

        .logout-btn {
            background-color: #ff4757;
            border: none;
            color: white;
        }

        .logout-btn:hover {
            background-color: #ff3347;
        }
    </style>
</head>
<body>
    <div class="account-container">
        <div class="profile-header">
            <div class="profile-pic">
                <i class='bx bxs-user'></i>
            </div>
            <div class="user-info">
                <h2><?php echo htmlspecialchars($user['first_name']) . " " . htmlspecialchars($user['last_name']); ?></h2>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
        </div>

        <div class="account-section">
            <h3>Account Details</h3>
            <div class="account-details">
                <div>
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['first_name']) . " " . htmlspecialchars($user['last_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
                </div>
                <button class="edit-btn">Edit Profile</button>
            </div>
        </div>

        <div class="account-section">
            <h3>Security</h3>
            <div class="account-details">
                <p>Password last changed: A while ago</p>
                <a href="password-recovery.html" class="action-btn">Change Password</a>
            </div>
        </div>

        <div class="action-buttons">
            <a href="user_dashboard.php" class="action-btn">Back to Dashboard</a>
            <button class="action-btn logout-btn">Logout</button>
        </div>
    </div>

    <script>
        // Logout functionality
        document.querySelector('.logout-btn').addEventListener('click', function() {
            // Simulate logout process
            alert('You have been logged out successfully!');
            window.location.href = 'login.php';
        });

        // Edit profile button
        document.querySelector('.edit-btn').addEventListener('click', function() {
            alert('Edit profile functionality coming soon!');
        });
    </script>
</body>
</html>
