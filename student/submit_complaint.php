<?php
require_once '../config/database.php';

// Check if user is logged in and is student
check_student();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = sanitize_input($_POST['category']);
    $title = sanitize_input($_POST['title']);
    $description = sanitize_input($_POST['description']);
    
    if (empty($category) || empty($title) || empty($description)) {
        $message = show_message('All fields are required!', 'danger');
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO complaints (user_id, category, title, description) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $category, $title, $description]);
            
            $message = show_message('Complaint submitted successfully! You will receive updates soon.', 'success');
            
            // Clear form data after successful submission
            $_POST = array();
        } catch (PDOException $e) {
            $message = show_message('Failed to submit complaint: ' . $e->getMessage(), 'danger');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint - Campus Assist Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">🎓 Campus Assist Portal</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php">🏠 Dashboard</a>
                <span class="navbar-text me-3">Welcome, <?php echo $_SESSION['name']; ?>!</span>
                <a class="nav-link" href="../auth/logout.php">🚪 Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h6>📋 Student Menu</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="dashboard.php" class="list-group-item list-group-item-action">
                            🏠 Dashboard
                        </a>
                        <a href="submit_complaint.php" class="list-group-item list-group-item-action active">
                            📝 Submit Complaint
                        </a>
                        <a href="my_complaints.php" class="list-group-item list-group-item-action">
                            📋 My Complaints
                        </a>
                        <a href="lost_found.php" class="list-group-item list-group-item-action">
                            🔍 Lost & Found
                        </a>
                        <a href="report_item.php" class="list-group-item list-group-item-action">
                            📦 Report Item
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h4>📝 Submit New Complaint</h4>
                        <p class="mb-0 text-light">Report any issue you're facing on campus</p>
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <form method="POST" action="" id="complaintForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category" class="form-label">Complaint Category *</label>
                                        <select class="form-select" id="category" name="category" required>
                                            <option value="">Select Category</option>
                                            <option value="academic" <?php echo (isset($_POST['category']) && $_POST['category'] == 'academic') ? 'selected' : ''; ?>>
                                                📚 Academic Issues
                                            </option>
                                            <option value="hostel" <?php echo (isset($_POST['category']) && $_POST['category'] == 'hostel') ? 'selected' : ''; ?>>
                                                🏠 Hostel Issues
                                            </option>
                                            <option value="transport" <?php echo (isset($_POST['category']) && $_POST['category'] == 'transport') ? 'selected' : ''; ?>>
                                                🚌 Transport Issues
                                            </option>
                                            <option value="campus_facility" <?php echo (isset($_POST['category']) && $_POST['category'] == 'campus_facility') ? 'selected' : ''; ?>>
                                                🏫 Campus Facility
                                            </option>
                                            <option value="other" <?php echo (isset($_POST['category']) && $_POST['category'] == 'other') ? 'selected' : ''; ?>>
                                                🔧 Other Issues
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Complaint Title *</label>
                                        <input type="text" class="form-control" id="title" name="title" 
                                               placeholder="Brief title of your complaint" 
                                               value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" 
                                               maxlength="200" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Detailed Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="6" 
                                          placeholder="Please provide detailed information about your complaint..." required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                                <div class="form-text">Be specific about the issue, when it occurred, and any relevant details.</div>
                            </div>
                            
                            <div class="alert alert-info">
                                <strong>ℹ️ Note:</strong> Your complaint will be reviewed by our admin team. You will receive updates on the status and any responses.
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    📤 Submit Complaint
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    🔄 Reset Form
                                </button>
                                <a href="dashboard.php" class="btn btn-outline-secondary">
                                    ↩️ Back to Dashboard
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Help Section -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>💡 Tips for Better Complaints</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>✅ Do:</h6>
                                <ul>
                                    <li>Be specific and clear</li>
                                    <li>Include relevant details (date, time, location)</li>
                                    <li>Choose appropriate category</li>
                                    <li>Be respectful in your language</li>
                                    <li>Provide contact information if needed</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>❌ Don't:</h6>
                                <ul>
                                    <li>Use offensive language</li>
                                    <li>Submit duplicate complaints</li>
                                    <li>Include personal attacks</li>
                                    <li>Submit false information</li>
                                    <li>Write in all caps</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        document.getElementById('complaintForm').addEventListener('submit', function(e) {
            const category = document.getElementById('category').value;
            const title = document.getElementById('title').value;
            const description = document.getElementById('description').value;
            
            if (!category || !title.trim() || !description.trim()) {
                e.preventDefault();
                alert('Please fill in all required fields!');
                return false;
            }
            
            if (title.length < 10) {
                e.preventDefault();
                alert('Title should be at least 10 characters long!');
                return false;
            }
            
            if (description.length < 20) {
                e.preventDefault();
                alert('Description should be at least 20 characters long!');
                return false;
            }
        });

        // Character counter for title
        document.getElementById('title').addEventListener('input', function() {
            const titleLength = this.value.length;
            const maxLength = 200;
            
            if (titleLength > maxLength - 20) {
                this.style.borderColor = '#ffc107';
            } else {
                this.style.borderColor = '#e3e3e3';
            }
        });

        // Auto-resize textarea
        document.getElementById('description').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    </script>
</body>
</html>