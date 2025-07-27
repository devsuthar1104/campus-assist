<?php
// includes/functions.php - Common Functions

// Redirect function
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Show message function
function show_message($message, $type = 'info') {
    $class = '';
    switch($type) {
        case 'success':
            $class = 'alert-success';
            break;
        case 'danger':
        case 'error':
            $class = 'alert-danger';
            break;
        case 'warning':
            $class = 'alert-warning';
            break;
        default:
            $class = 'alert-info';
    }
    
    return '<div class="alert ' . $class . ' alert-dismissible fade show" role="alert">
                ' . $message . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
}

// Check if user is logged in
function check_login() {
    if (!isset($_SESSION['user_id'])) {
        redirect('../auth/login.php');
    }
}

// Check if user is student
function check_student() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
        redirect('../auth/login.php');
    }
}

// Check if user is admin
function check_admin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        redirect('../auth/login.php');
    }
}

// Format date for display
function format_date($date) {
    return date('d M Y, h:i A', strtotime($date));
}

// Get status badge HTML
function get_status_badge($status) {
    $badges = [
        'pending' => '<span class="badge bg-warning">⏳ Pending</span>',
        'in_progress' => '<span class="badge bg-info">🔄 In Progress</span>',
        'resolved' => '<span class="badge bg-success">✅ Resolved</span>',
        'closed' => '<span class="badge bg-secondary">🔒 Closed</span>',
        'active' => '<span class="badge bg-primary">🔍 Active</span>'
    ];
    
    return $badges[$status] ?? '<span class="badge bg-secondary">' . ucfirst($status) . '</span>';
}
?>