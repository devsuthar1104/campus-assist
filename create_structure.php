<?php
/*
 * Campus Assist Portal - Automatic Folder & File Structure Creator
 * इस script को campus_assist folder में create_structure.php नाम से save करें
 * फिर browser में http://localhost/campus_assist/create_structure.php पर जाकर run करें
 */

echo "<h2>🚀 Campus Assist Portal - Structure Creator</h2>";
echo "<p>Creating folder structure and basic files...</p>";

// Define the folder structure
$folders = [
    'config',
    'auth', 
    'student',
    'admin',
    'assets/css',
    'assets/js', 
    'assets/images',
    'includes'
];

// Define files to create with basic content
$files = [
    // Config files
    'config/database.php' => '<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "campus_assist_db";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connected successfully!";
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>',

    // Auth files
    'auth/login.php' => '<?php
session_start();
// Login page - will be completed in next steps
echo "<h1>Login Page - Under Development</h1>";
?>',

    'auth/register.php' => '<?php
session_start(); 
// Registration page - will be completed in next steps
echo "<h1>Registration Page - Under Development</h1>";
?>',

    'auth/logout.php' => '<?php
session_start();
session_destroy();
header("Location: ../index.php");
exit();
?>',

    // Student files
    'student/dashboard.php' => '<?php
session_start();
// Student dashboard - will be completed in next steps
echo "<h1>Student Dashboard - Under Development</h1>";
?>',

    'student/submit_complaint.php' => '<?php
session_start();
// Submit complaint page - will be completed in next steps  
echo "<h1>Submit Complaint - Under Development</h1>";
?>',

    'student/my_complaints.php' => '<?php
session_start();
// My complaints page - will be completed in next steps
echo "<h1>My Complaints - Under Development</h1>";
?>',

    'student/lost_found.php' => '<?php
session_start();
// Lost & Found page - will be completed in next steps
echo "<h1>Lost & Found - Under Development</h1>";
?>',

    'student/report_item.php' => '<?php
session_start();
// Report item page - will be completed in next steps
echo "<h1>Report Item - Under Development</h1>";
?>',

    // Admin files
    'admin/dashboard.php' => '<?php
session_start();
// Admin dashboard - will be completed in next steps
echo "<h1>Admin Dashboard - Under Development</h1>";
?>',

    'admin/manage_complaints.php' => '<?php
session_start();
// Manage complaints - will be completed in next steps
echo "<h1>Manage Complaints - Under Development</h1>";
?>',

    'admin/manage_items.php' => '<?php
session_start();  
// Manage items - will be completed in next steps
echo "<h1>Manage Lost & Found Items - Under Development</h1>";
?>',

    // Include files
    'includes/header.php' => '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : "Campus Assist Portal"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>',

    'includes/footer.php' => '    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>',

    'includes/functions.php' => '<?php
// Common functions will be added here
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>',

    // CSS file
    'assets/css/style.css' => '/* Campus Assist Portal - Custom Styles */

body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8f9fa;
}

.navbar-brand {
    font-weight: bold;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
    transition: all 0.3s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.btn {
    border-radius: 0.375rem;
}

/* Add more styles as needed */
',

    // JavaScript file  
    'assets/js/script.js' => '// Campus Assist Portal - Custom JavaScript

// Form validation function
function validateForm() {
    // Add validation logic here
    return true;
}

// Search functionality
function searchItems(query) {
    // Add search logic here
    console.log("Searching for: " + query);
}

// Add more functions as needed
',

    // Homepage
    'index.php' => '<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Assist Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">🎓 Campus Assist Portal</a>
            <div class="navbar-nav ms-auto">
                <?php if (isset($_SESSION["user_id"])): ?>
                    <a class="nav-link" href="<?php echo $_SESSION["role"] == "admin" ? "admin/dashboard.php" : "student/dashboard.php"; ?>">Dashboard</a>
                    <a class="nav-link" href="auth/logout.php">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="auth/login.php">Login</a>
                    <a class="nav-link" href="auth/register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="display-4">Welcome to Campus Assist Portal</h1>
                <p class="lead">Your one-stop solution for campus complaints and lost & found items</p>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">📝 E-Complaint System</h5>
                        <p class="card-text">Submit and track complaints related to academics, hostel, transport, and campus facilities.</p>
                        <?php if (isset($_SESSION["user_id"])): ?>
                            <a href="student/submit_complaint.php" class="btn btn-primary">Submit Complaint</a>
                        <?php else: ?>
                            <a href="auth/login.php" class="btn btn-primary">Login to Submit</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">🔍 Lost & Found</h5>
                        <p class="card-text">Report lost items or help others find their belongings with our smart matching system.</p>
                        <?php if (isset($_SESSION["user_id"])): ?>
                            <a href="student/lost_found.php" class="btn btn-success">View Items</a>
                        <?php else: ?>
                            <a href="auth/login.php" class="btn btn-success">Login to Access</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p>&copy; 2024 Campus Assist Portal. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>'
];

// Create folders
echo "<h3>📁 Creating Folders:</h3>";
foreach ($folders as $folder) {
    if (!file_exists($folder)) {
        if (mkdir($folder, 0777, true)) {
            echo "✅ Created folder: <strong>$folder</strong><br>";
        } else {
            echo "❌ Failed to create folder: <strong>$folder</strong><br>";
        }
    } else {
        echo "⚡ Folder already exists: <strong>$folder</strong><br>";
    }
}

echo "<h3>📄 Creating Files:</h3>";
// Create files
foreach ($files as $filepath => $content) {
    if (file_put_contents($filepath, $content)) {
        echo "✅ Created file: <strong>$filepath</strong><br>";
    } else {
        echo "❌ Failed to create file: <strong>$filepath</strong><br>";
    }
}

echo "<br><h3>🎉 Structure Creation Complete!</h3>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Go to <a href='config/database.php' target='_blank'>config/database.php</a> to test database connection</li>";
echo "<li>Go to <a href='index.php' target='_blank'>index.php</a> to see your homepage</li>";
echo "<li>Create database tables using phpMyAdmin</li>";
echo "<li>Start building the complete functionality</li>";
echo "</ol>";

echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<strong>🔥 Pro Tip:</strong> Delete this create_structure.php file after use for security!";
echo "</div>";
?>'
];

// Main execution
echo "<h3>📁 Creating Folders:</h3>";
foreach ($folders as $folder) {
    if (!file_exists($folder)) {
        if (mkdir($folder, 0777, true)) {
            echo "✅ Created folder: <strong>$folder</strong><br>";
        } else {
            echo "❌ Failed to create folder: <strong>$folder</strong><br>";
        }
    } else {
        echo "⚡ Folder already exists: <strong>$folder</strong><br>";
    }
}

echo "<h3>📄 Creating Files:</h3>";
// Create files
foreach ($files as $filepath => $content) {
    if (file_put_contents($filepath, $content)) {
        echo "✅ Created file: <strong>$filepath</strong><br>";
    } else {
        echo "❌ Failed to create file: <strong>$filepath</strong><br>";
    }
}

echo "<br><h3>🎉 Structure Creation Complete!</h3>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Go to <a href='config/database.php' target='_blank'>config/database.php</a> to test database connection</li>";
echo "<li>Go to <a href='index.php' target='_blank'>index.php</a> to see your homepage</li>";
echo "<li>Create database tables using phpMyAdmin</li>";
echo "<li>Start building the complete functionality</li>";
echo "</ol>";

echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<strong>🔥 Pro Tip:</strong> Delete this create_structure.php file after use for security!";
echo "</div>";
?>