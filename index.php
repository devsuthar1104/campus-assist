<?php
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Assist Portal - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">🎓 Campus Assist Portal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a class="nav-link" href="<?php echo $_SESSION['role']; ?>/dashboard.php">
                            🏠 Dashboard
                        </a>
                        <span class="navbar-text me-3">Welcome, <?php echo $_SESSION['name']; ?>!</span>
                        <a class="nav-link" href="auth/logout.php">🚪 Logout</a>
                    <?php else: ?>
                        <a class="nav-link" href="auth/login.php">🔐 Login</a>
                        <a class="nav-link" href="auth/register.php">📝 Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container mt-5">
        <div class="hero-section text-center">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="display-4 mb-4">Welcome to Campus Assist Portal</h1>
                    <p class="lead mb-5">Your comprehensive solution for campus complaints and lost & found items</p>
                    
                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="auth/register.php" class="btn btn-primary btn-lg">Get Started</a>
                            <a href="auth/login.php" class="btn btn-outline-primary btn-lg">Login</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="row mt-5">
            <div class="col-md-6 mb-4">
                <div class="card h-100 fadeIn">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-clipboard-list fa-3x text-primary"></i>
                        </div>
                        <h3 class="card-title mb-3">📝 E-Complaint System</h3>
                        <p class="card-text mb-4">Submit and track complaints related to academics, hostel, transport, and campus facilities. Get real-time updates on your complaint status.</p>
                        
                        <div class="features-list text-start mb-4">
                            <small class="text-muted">
                                ✅ Multiple complaint categories<br>
                                ✅ Real-time status tracking<br>
                                ✅ Admin response system<br>
                                ✅ History management
                            </small>
                        </div>
                        
                        <a href="<?php echo isset($_SESSION['user_id']) ? 'student/submit_complaint.php' : 'auth/login.php'; ?>" 
                           class="btn btn-primary">
                            <?php echo isset($_SESSION['user_id']) ? 'File a Complaint' : 'Login to File Complaint'; ?>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card h-100 fadeIn">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-search fa-3x text-success"></i>
                        </div>
                        <h3 class="card-title mb-3">🧳 Lost & Found System</h3>
                        <p class="card-text mb-4">Report lost items or post found belongings to help fellow students recover their items. Smart matching system to connect students.</p>
                        
                        <div class="features-list text-start mb-4">
                            <small class="text-muted">
                                ✅ Report lost/found items<br>
                                ✅ Smart matching algorithm<br>
                                ✅ Location-based search<br>
                                ✅ Contact information system
                            </small>
                        </div>
                        
                        <a href="<?php echo isset($_SESSION['user_id']) ? 'student/lost_found.php' : 'auth/login.php'; ?>" 
                           class="btn btn-success">
                            <?php echo isset($_SESSION['user_id']) ? 'Browse Items' : 'Login to Browse Items'; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section (if user is logged in) -->
        <?php if(isset($_SESSION['user_id'])): ?>
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>📊 Quick Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <?php
                            // Get complaint stats
                            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM complaints WHERE user_id = ?");
                            $stmt->execute([$_SESSION['user_id']]);
                            $my_complaints = $stmt->fetch()['total'];
                            
                            // Get lost/found stats
                            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM lost_found_items WHERE user_id = ?");
                            $stmt->execute([$_SESSION['user_id']]);
                            $my_items = $stmt->fetch()['total'];
                            
                            // Get total active lost/found items
                            $stmt = $pdo->query("SELECT COUNT(*) as total FROM lost_found_items WHERE status = 'active'");
                            $active_items = $stmt->fetch()['total'];
                            ?>
                            
                            <div class="col-md-4">
                                <div class="dashboard-card bg-primary">
                                    <h4><?php echo $my_complaints; ?></h4>
                                    <p>My Complaints</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="dashboard-card bg-success">
                                    <h4><?php echo $my_items; ?></h4>
                                    <p>My Reported Items</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="dashboard-card bg-info">
                                    <h4><?php echo $active_items; ?></h4>
                                    <p>Active Lost/Found Items</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- How it Works Section -->
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>🚀 How It Works</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>📝 For Complaints:</h6>
                                <ol>
                                    <li>Register/Login to your account</li>
                                    <li>Choose appropriate complaint category</li>
                                    <li>Provide detailed description</li>
                                    <li>Submit and track status updates</li>
                                    <li>Receive admin responses</li>
                                </ol>
                            </div>
                            <div class="col-md-6">
                                <h6>🔍 For Lost & Found:</h6>
                                <ol>
                                    <li>Register/Login to your account</li>
                                    <li>Report lost or found items</li>
                                    <li>Browse existing items</li>
                                    <li>Use smart search features</li>
                                    <li>Connect with other students</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p>&copy; 2024 Campus Assist Portal. All rights reserved.</p>
                    <p>Made with ❤️ for students, by students</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>