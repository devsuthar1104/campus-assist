<?php
require_once '../config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $message = show_message('Please fill in all fields!', 'danger');
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['student_id'] = $user['student_id'];
                
                if ($user['role'] == 'admin') {
                    redirect('../admin/dashboard.php');
                } else {
                    redirect('../student/dashboard.php');
                }
            } else {
                $message = show_message('Invalid email or password!', 'danger');
            }
        } catch (PDOException $e) {
            $message = show_message('Login failed: ' . $e->getMessage(), 'danger');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Campus Assist Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">🎓 Campus Assist Portal</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="register.php">📝 Register</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card fadeIn">
                    <div class="card-header text-center">
                        <h4>🔐 Login to Your Account</h4>
                        <p class="mb-0 text-light">Access your campus assistance portal</p>
                    </div>
                    <div class="card-body p-4">
                        <?php echo $message; ?>
                        
                        <form method="POST" action="" id="loginForm">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="Enter your email address" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Enter your password" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                🔓 Login
                            </button>
                        </form>
                        
                        <div class="text-center">
                            <p class="mb-3">Don't have an account? 
                                <a href="register.php" class="text-decoration-none">Register here</a>
                            </p>
                            
                            <hr>
                            
                            <div class="alert alert-info">
                                <strong>🔑 Admin Access:</strong><br>
                                <small>
                                    Email: <code>admin@campus.com</code><br>
                                    Password: <code>admin123</code>
                                </small>
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
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields!');
                return false;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address!');
                return false;
            }
        });
    </script>
</body>
</html>