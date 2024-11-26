<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database Configuration
$host = 'localhost';
$db = 'campusfixit';
$user = 'root';
$pass = 'yEnma123@';

// Database Connection
try {
    $conn = new mysqli($host, $user, $pass, $db);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Database Connection Error: " . $e->getMessage());
}

// Start session
session_start();

// Initialize flash message
$flash = [
    'message' => '',
    'type' => ''
];

// Dummy user session (remove in actual implementation)
$_SESSION['user_id'] = 1;

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Basic input validation
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $issueType = $_POST['issueType'] ?? '';
        $description = $_POST['description'] ?? '';

        // Validate inputs
        if (empty($name) || empty($email) || empty($issueType) || empty($description)) {
            throw new Exception('All fields are required');
        }

        // Sanitize inputs
        $name = $conn->real_escape_string($name);
        $email = $conn->real_escape_string($email);
        $issueType = $conn->real_escape_string($issueType);
        $description = $conn->real_escape_string($description);

        // Image upload handling
        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image'];
            
            // Create uploads directory if not exists
            $uploadDir = 'uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique filename
            $imageName = uniqid() . '_' . basename($image['name']);
            $imagePath = $uploadDir . $imageName;

            // Move uploaded file
            if (move_uploaded_file($image['tmp_name'], $imagePath)) {
                // File uploaded successfully
                $imagePath = $conn->real_escape_string($imagePath);
            } else {
                throw new Exception('Failed to upload image');
            }
        }

        // Prepare SQL query
        $sql = "INSERT INTO maintenance_reports (name, email, issue_type, description, image) 
                VALUES ('$name', '$email', '$issueType', '$description', '$imagePath')";

        // Execute query
        if ($conn->query($sql) === TRUE) {
            $flash = [
                'message' => 'Report submitted successfully! Your report ID is: ' . $conn->insert_id,
                'type' => 'success'
            ];
        } else {
            throw new Exception('Database error: ' . $conn->error);
        }
    } catch (Exception $e) {
        $flash = [
            'message' => $e->getMessage(),
            'type' => 'error'
        ];
        
        // Log error (you can replace this with proper logging)
        error_log($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Maintenance Report - CampusFixIt</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url('../../assets/images/background/nature-7047433_1280.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(26, 26, 46, 0.95);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }

        .overlay a {
            color: #7f5af0;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .overlay ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .overlay ul li {
            margin-left: 2rem;
        }

        .overlay ul li a {
            color: #e1e1e1;
            text-decoration: none;
            font-size: 1rem;
        }

        .container {
            width: 90%;
            max-width: 600px;
            margin: 100px auto 0;
            background: rgba(26, 26, 46, 0.95);
            padding: 2rem;
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #7f5af0;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 1rem;
            color: #7f5af0;
        }

        input, select, textarea {
            width: 100%;
            padding: 0.8rem;
            margin-top: 0.5rem;
            border: none;
            border-radius: 5px;
            background-color: rgba(40, 40, 70, 0.7);
            color: #e1e1e1;
        }

        textarea {
            height: 100px;
        }

        button {
            background: #7f5af0;
            color: white;
            cursor: pointer;
            margin-top: 1.5rem;
            padding: 0.8rem;
            border: none;
            border-radius: 5px;
        }

        button:hover {
            background: #5a2dba;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
        }

        .alert-success {
            background-color: rgba(40, 200, 40, 0.2);
            border: 1px solid green;
            color: green;
        }

        .alert-error {
            background-color: rgba(200, 40, 40, 0.2);
            border: 1px solid red;
            color: red;
        }
    </style>
</head>
<body>
    <nav class="overlay">
        <a href="user_dashboard.html">CampusFixIt</a>
        <ul>
            <li><a href="user_dashboard.php">Home</a></li>
            <li><a href="trackreports.php">Track Reports</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Submit Maintenance Report</h1>
        
        <?php if (!empty($flash['message'])): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" required>
            
            <label for="issueType">Type of Issue</label>
            <select id="issueType" name="issueType" required>
                <option value="">Select an issue type</option>
                <option value="lighting">Lighting</option>
                <option value="plumbing">Plumbing</option>
                <option value="network">Network</option>
                <option value="hvac">HVAC</option>
                <option value="structural">Structural</option>
                <option value="cleaning">Cleaning</option>
            </select>
            
            <label for="description">Issue Description</label>
            <textarea id="description" name="description" required></textarea>
            
            <label for="image">Upload an Image (optional)</label>
            <input type="file" id="image" name="image" accept="image/*">
            
            <button type="submit">Submit Report</button>
        </form>
    </div>
</body>
</html>
<?php
// Close database connection
$conn->close();
?>