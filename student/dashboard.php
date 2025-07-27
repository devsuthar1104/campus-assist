<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../auth/login.php');
    exit();
}

// Get user info
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Get complaint stats
$complaint_stats = $pdo->prepare("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
        SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved
    FROM complaints WHERE user_id = ?
");
$complaint_stats->execute([$user_id]);
$stats = $complaint_stats->fetch();

// Get recent complaints
$recent_complaints = $pdo->prepare("
    SELECT * FROM complaints 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 5
");
$recent_complaints->execute([$user_id]);
$complaints = $recent_complaints->fetchAll();
?>

<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Campus Assist</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <a href="dashboard.php" class="nav-link active">
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
                <a href="lost_found.php" class="quick-link-card">
                    <i class="fas fa-search"></i>
                            <h4>Lost & Found</h4>
                                <p>Find lost items or report found items</p>
                </a>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-content">
                <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?>! 👋</h1>
                <p>Student ID: <?php echo htmlspecialchars($user['student_id']); ?></p>
            </div>
            <div class="quick-actions">
                <a href="submit_complaint.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Complaint
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['total'] ?: 0; ?></h3>
                    <p>Total Complaints</p>
                </div>
            </div>

            <div class="stat-card pending">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['pending'] ?: 0; ?></h3>
                    <p>Pending</p>
                </div>
            </div>

            <div class="stat-card progress">
                <div class="stat-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['in_progress'] ?: 0; ?></h3>
                    <p>In Progress</p>
                </div>
            </div>

            <div class="stat-card resolved">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['resolved'] ?: 0; ?></h3>
                    <p>Resolved</p>
                </div>
            </div>
        </div>

        <!-- Recent Complaints -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2><i class="fas fa-history"></i> Recent Complaints</h2>
                <a href="my_complaints.php" class="btn btn-outline">View All</a>
            </div>

            <?php if (empty($complaints)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No complaints yet</h3>
                    <p>Start by submitting your first complaint</p>
                    <a href="submit_complaint.php" class="btn btn-primary">Submit Complaint</a>
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
                                <p><i class="fas fa-calendar"></i> <?php echo date('d M Y', strtotime($complaint['created_at'])); ?></p>
                                <?php if ($complaint['priority'] === 'high'): ?>
                                    <p><i class="fas fa-exclamation-triangle text-danger"></i> High Priority</p>
                                <?php endif; ?>
                            </div>
                            <div class="complaint-description">
                                <p><?php echo htmlspecialchars(substr($complaint['description'], 0, 100)) . '...'; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Quick Links -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2><i class="fas fa-link"></i> Quick Links</h2>
            </div>
            <div class="quick-links-grid">
                <a href="submit_complaint.php" class="quick-link-card">
                    <i class="fas fa-plus-circle"></i>
                    <h4>Submit New Complaint</h4>
                    <p>Report any campus issue or problem</p>
                </a>
                <a href="my_complaints.php" class="quick-link-card">
                    <i class="fas fa-list-ul"></i>
                    <h4>Track Complaints</h4>
                    <p>View status of all your complaints</p>
                </a>
                <a href="#" class="quick-link-card">
                    <i class="fas fa-search"></i>
                    <h4>Lost & Found</h4>
                    <p>Find lost items or report found items</p>
                </a>
                <a href="#" class="quick-link-card">
                    <i class="fas fa-phone"></i>
                    <h4>Emergency Contact</h4>
                    <p>Quick access to emergency numbers</p>
                </a>
            </div>
        </div>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>