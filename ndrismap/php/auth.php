<?php
/**
 * Authentication Module
 * NDRIS-Nepal System
 * 
 * Handles admin login, logout, and session management
 * Uses password hashing for security
 */

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';

/**
 * Admin login function
 * @param string $username Username
 * @param string $password Plain text password
 * @return array Result with success status and message
 */
function admin_login($username, $password) {
    // Validate inputs
    if (empty($username) || empty($password)) {
        return ['success' => false, 'message' => 'Username and password are required'];
    }
    
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    // Query to get user
    $sql = "SELECT id, username, password_hash FROM admin_users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to prepare statement'];
    }
    
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $user = mysqli_fetch_assoc($result);
    
    mysqli_stmt_close($stmt);
    close_db_connection($conn);
    
    // Check if user exists and password matches
    if ($user && password_verify($password, $user['password_hash'])) {
        // Set session variables
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['login_time'] = time();
        
        return ['success' => true, 'message' => 'Login successful'];
    } else {
        return ['success' => false, 'message' => 'Invalid username or password'];
    }
}

/**
 * Admin logout function
 * @return array Result with success status
 */
function admin_logout() {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Destroy the session
    session_destroy();
    
    return ['success' => true, 'message' => 'Logged out successfully'];
}

/**
 * Check if admin is logged in
 * @return bool True if logged in, false otherwise
 */
function is_admin_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Require admin authentication (redirect if not logged in)
 * @param string $redirect_url URL to redirect if not authenticated
 */
function require_admin_auth($redirect_url = '../public/index.php') {
    if (!is_admin_logged_in()) {
        header("Location: $redirect_url");
        exit();
    }
}

/**
 * Get current admin info
 * @return array|null Admin info or null if not logged in
 */
function get_current_admin() {
    if (!is_admin_logged_in()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['admin_id'] ?? null,
        'username' => $_SESSION['admin_username'] ?? null,
        'login_time' => $_SESSION['login_time'] ?? null
    ];
}

/**
 * Change admin password
 * @param int $admin_id Admin user ID
 * @param string $old_password Current password
 * @param string $new_password New password
 * @return array Result with success status
 */
function change_admin_password($admin_id, $old_password, $new_password) {
    // Validate inputs
    if (empty($old_password) || empty($new_password)) {
        return ['success' => false, 'message' => 'Both old and new passwords are required'];
    }
    
    if (strlen($new_password) < 6) {
        return ['success' => false, 'message' => 'New password must be at least 6 characters'];
    }
    
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    // Verify old password
    $sql = "SELECT password_hash FROM admin_users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to prepare statement'];
    }
    
    mysqli_stmt_bind_param($stmt, "i", $admin_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    if (!$user || !password_verify($old_password, $user['password_hash'])) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Current password is incorrect'];
    }
    
    // Update with new password
    $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
    $sql = "UPDATE admin_users SET password_hash = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to prepare statement'];
    }
    
    mysqli_stmt_bind_param($stmt, "si", $new_hash, $admin_id);
    $result = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    close_db_connection($conn);
    
    if ($result) {
        return ['success' => true, 'message' => 'Password changed successfully'];
    } else {
        return ['success' => false, 'message' => 'Failed to update password'];
    }
}

/**
 * Create new admin user (for initial setup or admin management)
 * @param string $username Username
 * @param string $password Password
 * @return array Result with success status
 */
function create_admin_user($username, $password) {
    // Validate inputs
    if (empty($username) || empty($password)) {
        return ['success' => false, 'message' => 'Username and password are required'];
    }
    
    if (strlen($password) < 6) {
        return ['success' => false, 'message' => 'Password must be at least 6 characters'];
    }
    
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    // Check if username already exists
    $check_sql = "SELECT id FROM admin_users WHERE username = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $username);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (mysqli_num_rows($check_result) > 0) {
        mysqli_stmt_close($check_stmt);
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Username already exists'];
    }
    mysqli_stmt_close($check_stmt);
    
    // Create new user
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO admin_users (username, password_hash) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to prepare statement'];
    }
    
    mysqli_stmt_bind_param($stmt, "ss", $username, $password_hash);
    $result = mysqli_stmt_execute($stmt);
    
    if ($result) {
        $user_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => true, 'message' => 'Admin user created successfully', 'id' => $user_id];
    } else {
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to create admin user'];
    }
}

// Handle AJAX requests if called directly
if (basename($_SERVER['PHP_SELF']) == 'auth.php' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'login':
            $username = sanitize_input($_POST['username'] ?? '');
            $password = $_POST['password'] ?? ''; // Don't sanitize password (keeps special chars)
            $result = admin_login($username, $password);
            json_response($result['success'], $result['message']);
            break;
            
        case 'logout':
            $result = admin_logout();
            json_response($result['success'], $result['message']);
            break;
            
        case 'check_auth':
            $logged_in = is_admin_logged_in();
            $admin = $logged_in ? get_current_admin() : null;
            json_response($logged_in, '', $admin);
            break;
            
        case 'change_password':
            if (!is_admin_logged_in()) {
                json_response(false, 'Not authenticated');
            }
            $admin = get_current_admin();
            $old_password = $_POST['old_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $result = change_admin_password($admin['id'], $old_password, $new_password);
            json_response($result['success'], $result['message']);
            break;
            
        default:
            json_response(false, 'Invalid action');
    }
}
?>
