<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get all user complaints
$stmt = $pdo->prepare("
    SELECT * FROM complaints 
    WHERE user_id = ? 
    ORDER BY created_at DESC
");
$stmt->execute([$user_id]);
$complaints = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Complaints - Campus Assist</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Same navigation as dashboard -->
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
                <a href="my_complaints.php" class="nav-link active">
                    <i class="fas fa-list"></i> My Complaints
                </a>
                <a href="../auth/logout.php" class="nav-link logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="dashboard-section">
            <div class="section-header">
                <h2><i class="fas fa-list"></i> My Complaints</h2>
                <a href="submit_complaint.php" class="btn-primary">
                    <i class="fas fa-plus"></i> New Complaint
                </a>
            </div>

            <?php if (empty($complaints)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No complaints submitted yet</h3>
                    <p>Submit your first complaint to get started</p>
                    <a href="submit_complaint.php" class="btn-primary">Submit Complaint</a>
                </div>
            <?php else: ?>
                <div class="complaints-list">
                    <?php foreach ($complaints as $complaint): ?>
                        <div class="complaint-card">
                            <div class="complaint-header">
                                <h4><?php echo htmlspecialchars($complaint['title']); ?></h4>
                                <span class="status-badge status-<?php echo $complaint['status']; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                                </span>
                            </div>
                            <div class="complaint-info">
                                <p><i class="fas fa-tag"></i> <?php echo ucfirst($complaint['category']); ?></p>
                                <p><i class="fas fa-calendar"></i> <?php echo date('d M Y, H:i', strtotime($complaint['created_at'])); ?></p>
                                <?php if (isset($complaint['priority']) && $complaint['priority'] === 'high'): ?>
                                    <p><i class="fas fa-exclamation-triangle" style="color: #f5576c;"></i> High Priority</p>
                                <?php endif; ?>
                            </div>
                            <div class="complaint-description">
                                <p><?php echo htmlspecialchars($complaint['description']); ?></p>
                            </div>
                            <?php if (!empty($complaint['admin_response'])): ?>
                                <div class="admin-response">
                                    <h5><i class="fas fa-reply"></i> Admin Response:</h5>
                                    <p><?php echo htmlspecialchars($complaint['admin_response']); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>