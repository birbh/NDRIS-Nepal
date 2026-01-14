<?php
/**
 * Database Connection Module
 * NDRIS-Nepal System
 * 
 * Provides secure MySQLi connection for all database operations
 * Uses procedural PHP with error handling
 */

// Database configuration constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ndris_nepal');

/**
 * Establish database connection
 * @return mysqli|false Returns MySQLi connection object or false on failure
 */
function get_db_connection() {
    // Create connection using MySQLi
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if (!$conn) {
        error_log("Database connection failed: " . mysqli_connect_error());
        return false;
    }
    
    // Set charset to UTF-8 for proper character handling
    mysqli_set_charset($conn, "utf8mb4");
    
    return $conn;
}

/**
 * Close database connection
 * @param mysqli $conn The connection to close
 */
function close_db_connection($conn) {
    if ($conn) {
        mysqli_close($conn);
    }
}

/**
 * Sanitize input to prevent XSS attacks
 * @param string $data Input data to sanitize
 * @return string Sanitized data
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validate district name
 * @param string $district District name to validate
 * @return bool True if valid, false otherwise
 */
function validate_district($district) {
    // List of valid districts in Nepal (simplified)
    $valid_districts = [
        'Kathmandu', 'Lalitpur', 'Bhaktapur', 'Pokhara', 'Gorkha',
        'Sunsari', 'Morang', 'Dhading', 'Chitwan', 'Jajarkot',
        'Kaski', 'Rupandehi', 'Banke', 'Kailali', 'Dang'
    ];
    
    return in_array($district, $valid_districts);
}

/**
 * Return JSON response
 * @param bool $success Success status
 * @param string $message Response message
 * @param mixed $data Optional data to include
 */
function json_response($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}
?>
