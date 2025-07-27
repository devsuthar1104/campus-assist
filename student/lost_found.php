<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submissions
if ($_POST) {
    if (isset($_POST['report_lost'])) {
        $item_name = trim($_POST['item_name']);
        $description = trim($_POST['description']);
        $lost_location = trim($_POST['lost_location']);
        $lost_date = $_POST['lost_date'];
        $contact_info = trim($_POST['contact_info']);
        
        if (!empty($item_name) && !empty($description)) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO lost_found (user_id, type, item_name, description, location, date_reported, contact_info, status, created_at) 
                    VALUES (?, 'lost', ?, ?, ?, ?, ?, 'active', NOW())
                ");
                $stmt->execute([$user_id, $item_name, $description, $lost_location, $lost_date, $contact_info]);
                $success_message = "Lost item reported successfully!";
            } catch (Exception $e) {
                $error_message = "Error reporting lost item. Please try again.";
            }
        } else {
            $error_message = "Please fill all required fields.";
        }
    }
    
    if (isset($_POST['report_found'])) {
        $item_name = trim($_POST['found_item_name']);
        $description = trim($_POST['found_description']);
        $found_location = trim($_POST['found_location']);
        $found_date = $_POST['found_date'];
        $contact_info = trim($_POST['found_contact_info']);
        
        if (!empty($item_name) && !empty($description)) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO lost_found (user_id, type, item_name, description, location, date_reported, contact_info, status, created_at) 
                    VALUES (?, 'found', ?, ?, ?, ?, ?, 'active', NOW())
                ");
                $stmt->execute([$user_id, $item_name, $description, $found_location, $found_date, $contact_info]);
                $success_message = "Found item reported successfully!";
            } catch (Exception $e) {
                $error_message = "Error reporting found item. Please try again.";
            }
        } else {
            $error_message = "Please fill all required fields.";
        }
    }
}

// Get recent lost items
try {
    $lost_items = $pdo->prepare("
        SELECT lf.*, u.name as reporter_name 
        FROM lost_found lf 
        JOIN users u ON lf.user_id = u.id 
        WHERE lf.type = 'lost' AND lf.status = 'active' 
        ORDER BY lf.created_at DESC 
        LIMIT 10
    ");
    $lost_items->execute();
    $lost_items_data = $lost_items->fetchAll();
} catch (Exception $e) {
    $lost_items_data = [];
}

// Get recent found items
try {
    $found_items = $pdo->prepare("
        SELECT lf.*, u.name as reporter_name 
        FROM lost_found lf 
        JOIN users u ON lf.user_id = u.id 
        WHERE lf.type = 'found' AND lf.status = 'active' 
        ORDER BY lf.created_at DESC 
        LIMIT 10
    ");
    $found_items->execute();
    $found_items_data = $found_items->fetchAll();
} catch (Exception $e) {
    $found_items_data = [];
}
?>

<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found - Campus Assist</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <i class="fas fa-graduation-cap"></i>
                <span>Campus Assist</span>
            </div>
            <div class="nav-menu">
                <a href="dashboard.php" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="submit_complaint.php" class="nav-link">
                    <i class="fas fa-plus-circle"></i> Submit Complaint
                </a>
                <a href="my_complaints.php" class="nav-link">
                    <i class="fas fa-list"></i> My Complaints
                </a>
                <a href="../auth/logout.php" class="nav-link logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- Page Header -->
        <div class="welcome-section">
            <div class="welcome-content">
                <h1><i class="fas fa-search"></i> Lost & Found Portal</h1>
                <p>Report lost items or help others find their belongings</p>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="lost-found-actions">
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#reportLostModal">
                <i class="fas fa-exclamation-triangle"></i> Report Lost Item
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reportFoundModal">
                <i class="fas fa-check-circle"></i> Report Found Item
            </button>
        </div>

        <!-- Lost Items Section -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2><i class="fas fa-exclamation-triangle text-warning"></i> Lost Items</h2>
            </div>
            
            <?php if (empty($lost_items_data)): ?>
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <h3>No lost items reported</h3>
                    <p>Be the first to report a lost item</p>
                </div>
            <?php else: ?>
                <div class="lost-found-grid">
                    <?php foreach ($lost_items_data as $item): ?>
                        <div class="lost-found-card lost-item">
                            <div class="item-header">
                                <h4><?php echo htmlspecialchars($item['item_name']); ?></h4>
                                <span class="item-type lost"><i class="fas fa-exclamation-triangle"></i> Lost</span>
                            </div>
                            <div class="item-details">
                                <p><strong>Description:</strong> <?php echo htmlspecialchars($item['description']); ?></p>
                                <p><i class="fas fa-map-marker-alt"></i> Last seen: <?php echo htmlspecialchars($item['location']); ?></p>
                                <p><i class="fas fa-calendar"></i> Date: <?php echo date('d M Y', strtotime($item['date_reported'])); ?></p>
                                <p><i class="fas fa-user"></i> Reported by: <?php echo htmlspecialchars($item['reporter_name']); ?></p>
                                <?php if (!empty($item['contact_info'])): ?>
                                    <p><i class="fas fa-phone"></i> Contact: <?php echo htmlspecialchars($item['contact_info']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Found Items Section -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2><i class="fas fa-check-circle text-success"></i> Found Items</h2>
            </div>
            
            <?php if (empty($found_items_data)): ?>
                <div class="empty-state">
                    <i class="fas fa-hand-holding"></i>
                    <h3>No found items reported</h3>
                    <p>Help others by reporting found items</p>
                </div>
            <?php else: ?>
                <div class="lost-found-grid">
                    <?php foreach ($found_items_data as $item): ?>
                        <div class="lost-found-card found-item">
                            <div class="item-header">
                                <h4><?php echo htmlspecialchars($item['item_name']); ?></h4>
                                <span class="item-type found"><i class="fas fa-check-circle"></i> Found</span>
                            </div>
                            <div class="item-details">
                                <p><strong>Description:</strong> <?php echo htmlspecialchars($item['description']); ?></p>
                                <p><i class="fas fa-map-marker-alt"></i> Found at: <?php echo htmlspecialchars($item['location']); ?></p>
                                <p><i class="fas fa-calendar"></i> Date: <?php echo date('d M Y', strtotime($item['date_reported'])); ?></p>
                                <p><i class="fas fa-user"></i> Found by: <?php echo htmlspecialchars($item['reporter_name']); ?></p>
                                <?php if (!empty($item['contact_info'])): ?>
                                    <p><i class="fas fa-phone"></i> Contact: <?php echo htmlspecialchars($item['contact_info']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Report Lost Item Modal -->
    <div class="modal fade" id="reportLostModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Report Lost Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Item Name *</label>
                            <input type="text" class="form-control" name="item_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" name="description" rows="3" required placeholder="Describe the item in detail..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Last Seen Location</label>
                            <input type="text" class="form-control" name="lost_location" placeholder="e.g., Library, Cafeteria, Parking">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date Lost</label>
                            <input type="date" class="form-control" name="lost_date" max="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Information</label>
                            <input type="text" class="form-control" name="contact_info" placeholder="Phone number or email">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="report_lost" class="btn btn-warning">Report Lost Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Report Found Item Modal -->
    <div class="modal fade" id="reportFoundModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-check-circle"></i> Report Found Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Item Name *</label>
                            <input type="text" class="form-control" name="found_item_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" name="found_description" rows="3" required placeholder="Describe the found item..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Found Location</label>
                            <input type="text" class="form-control" name="found_location" placeholder="Where did you find it?">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date Found</label>
                            <input type="date" class="form-control" name="found_date" max="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Your Contact Information</label>
                            <input type="text" class="form-control" name="found_contact_info" placeholder="How can the owner contact you?">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="report_found" class="btn btn-success">Report Found Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>