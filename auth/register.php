<?php
require_once '../config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = sanitize_input($_POST['student_id']);
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($student_id) || empty($name) || empty($email) || empty($password)) {
        $message = show_message('Please fill in all required fields!', 'danger');
    } elseif ($password !== $confirm_password) {
        $message = show_message('Passwords do not match!', 'danger');
    } elseif (strlen($password) < 6) {
        $message = show_message('Password must be at least 6 characters long!', 'danger');
    } else {
        try {
            // Check if student ID or email already exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE student_id = ? OR email = ?");
            $stmt->execute([$student_id, $email]);
            
            if ($stmt->fetch()) {
                $message = show_message('Student ID or Email already exists!', 'danger');
            } else {
                // Hash password and insert user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                $stmt = $pdo->prepare("INSERT INTO users (student_id, name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$student_id, $name, $email, $phone, $hashed_password]);
                
                $message = show_message('Registration successful! You can now login.', 'success');
                
                // Clear form data
                $_POST = array();
            }
        } catch (PDOException $e) {
            $message = show_message('Registration failed: ' . $e->getMessage(), 'danger');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Campus Assist Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">🎓 Campus Assist Portal</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="login.php">🔐 Login</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card fadeIn">
                    <div class="card-header text-center">
                        <h4>📝 Create Your Account</h4>
                        <p class="mb-0 text-light">Join the campus assistance community</p>
                    </div>
                    <div class="card-body p-4">
                        <?php echo $message; ?>
                        
                        <form method="POST" action="" id="registerForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="student_id" class="form-label">Student ID *</label>
                                        <input type="text" class="form-control" id="student_id" name="student_id" 
                                               placeholder="e.g., STU2024001"
                                               value="<?php echo isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : ''; ?>" 
                                               required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               placeholder="Enter your full name"
                                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                                               required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               placeholder="your.email@college.com"
                                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                               required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               placeholder="10-digit phone number"
                                               value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password *</label>
                                        <input type="password" class="form-control" id="password" name="password" 
                                               placeholder="At least 6 characters" required>
                                        <div class="form-text">Password should be at least 6 characters long</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirm Password *</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                               placeholder="Re-enter your password" required>
                                        <div id="passwordMatch" class="form-text"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a>
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                🎯 Create Account
                            </button>
                        </form>
                        
                        <div class="text-center">
                            <p class="mb-0">Already have an account? 
                                <a href="login.php" class="text-decoration-none">Login here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password match validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const matchDiv = document.getElementById('passwordMatch');
            
            if (confirmPassword) {
                if (password === confirmPassword) {
                    matchDiv.innerHTML = '<span class="text-success">✅ Passwords match</span>';
                    this.style.borderColor = '#28a745';
                } else {
                    matchDiv.innerHTML = '<span class="text-danger">❌ Passwords do not match</span>';
                    this.style.borderColor = '#dc3545';
                }
            } else {
                matchDiv.innerHTML = '';
                this.style.borderColor = '#e3e3e3';
            }
        });

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const studentId = document.getElementById('student_id').value;
            const email = document.getElementById('email').value;
            const name = document.getElementById('name').value;
            
            // Required fields validation
            if (!studentId || !name || !email || !password) {
                e.preventDefault();
                alert('Please fill in all required fields!');
                return false;
            }
            
            // Password validation
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return false;
            }
            
            // Password match validation
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address!');
                return false;
            }
            
            // Student ID validation
            if (studentId.length < 5) {
                e.preventDefault();
                alert('Student ID should be at least 5 characters long!');
                return false;
            }
        });

        // Phone number validation
        document.getElementById('phone').addEventListener('input', function() {
            const phone = this.value.replace(/\D/g, ''); // Remove non-digits
            this.value = phone;
            
            if (phone.length === 10) {
                this.style.borderColor = '#28a745';
            } else if (phone.length > 0) {
                this.style.borderColor = '#ffc107';
            } else {
                this.style.borderColor = '#e3e3e3';
            }
        });
    </script>
</body>
</html>