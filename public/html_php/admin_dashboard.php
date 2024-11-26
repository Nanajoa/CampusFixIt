<?php
require_once '../../config.php'; 

session_start();

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Database connection
require_once 'db_connection.php';

function submitReport($conn, $name, $issue, $pictures) {
    // Begin transaction
    $conn->begin_transaction();
    try {
        // Insert report details
        $stmt = $conn->prepare("INSERT INTO maintenance_reports (name, issue) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $issue);
        $stmt->execute();
        $reportId = $conn->insert_id;

        // Upload and store pictures
        $uploadDir = "uploads/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $pictureStmt = $conn->prepare("INSERT INTO report_pictures 
            (report_id, filename, original_filename, file_path, file_size, file_type) 
            VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($pictures['tmp_name'] as $key => $tmpName) {
            if ($pictures['error'][$key] == 0) {
                $originalName = $pictures['name'][$key];
                $fileExt = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $uniqueName = uniqid() . '_' . $originalName;
                $filePath = $uploadDir . $uniqueName;

                // Validate file type and size
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($fileExt, $allowedTypes) && $pictures['size'][$key] <= 5 * 1024 * 1024) {
                    if (move_uploaded_file($tmpName, $filePath)) {
                        $pictureStmt->bind_param(
                            "isssis", 
                            $reportId, 
                            $uniqueName, 
                            $originalName, 
                            $filePath, 
                            $pictures['size'][$key], 
                            $fileExt
                        );
                        $pictureStmt->execute();
                    }
                }
            }
        }

        // Commit transaction
        $conn->commit();
        return $reportId;
    } catch (Exception $e) {
        // Rollback in case of error
        $conn->rollback();
        return false;
    }
}

// Handle report submission from admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['issue'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $issue = $conn->real_escape_string($_POST['issue']);
    
    if (isset($_FILES['pictures'])) {
        $reportId = submitReport($conn, $name, $issue, $_FILES['pictures']);
        if ($reportId) {
            // Redirect or show success message
            $_SESSION['message'] = "Report submitted successfully!";
        } else {
            $_SESSION['error'] = "Report submission failed.";
        }
    }
}

// Fetch reports
$query = "SELECT * FROM maintenance_reports ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CampusFixIt Admin Dashboard</title>
    <style>
        /* Add your CSS styles here */
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Header Styles */
        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
            color: #fff;
        }

        .nav-links {
            display: flex;
            justify-content: flex-end;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
        }

        /* Main Content Styles */
        main {
            padding: 20px;
        }

        .add-report-section,
        .recent-reports-section {
            margin-bottom: 40px;
        }

        .add-report-section form,
        .recent-reports-section table {
            width: 100%;
        }

        .add-report-section input,
        .add-report-section button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .recent-reports-section table {
            border-collapse: collapse;
        }

        .recent-reports-section th,
        .recent-reports-section td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        /* Footer Styles */
        footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="#" class="logo">CampusFixIt Admin</a>
            <div class="nav-links">
                <a href="logout.php">Log Out</a>
            </div>
        </nav>
    </header>

    <main>
        <section class="add-report-section">
            <h2>Add New Report</h2>
            <form action="admin_dashboard.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Name" required>
                <input type="text" name="issue" placeholder="Issue Description" required>
                <input type="file" name="pictures[]" multiple accept="image/*">
                <button type="submit">Submit Report</button>
            </form>
        </section>

        <section class="recent-reports-section">
            <h2>Recent Reports</h2>
            <table>
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Name</th>
                        <th>Issue</th>
                        <th>Status</th>
                        <th>Pictures</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['report_id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['issue']; ?></td>
                            <td>
                                <form action="admin_dashboard.php" method="POST">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Resolved" <?php echo $row['status'] == 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                                        <option value="In Progress" <?php echo $row['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                    </select>
                                    <input type="hidden" name="reportId" value="<?php echo $row['report_id']; ?>">
                                </form>
                            </td>
                            <td>
                                <?php 
                                if (!empty($row['pictures'])) {
                                    $pictures = explode(',', $row['pictures']);
                                    foreach ($pictures as $pic) {
                                        echo "<img src='$pic' width='50' height='50' style='margin-right: 5px;'>";
                                    }
                                }
                                ?>
                                <form action="admin_dashboard.php" method="POST" enctype="multipart/form-data">
                                    <input type="file" name="picture" accept="image/*">
                                    <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
                                    <button type="submit">Upload</button>
                                </form>
                            </td>
                            <td><a href="#">View Details</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; 2024 CampusFixIt. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

<?php
$conn->close();
?>