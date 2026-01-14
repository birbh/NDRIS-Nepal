<?php
/**
 * Grievance CRUD Module
 * NDRIS-Nepal System
 * 
 * Handles Create, Read, Update, Delete operations for citizen grievances
 * Uses MySQLi prepared statements for security
 */

require_once 'db.php';

/**
 * Create a new grievance record
 * @param string $category Grievance category (infrastructure, health, education, etc.)
 * @param string $district District where issue exists
 * @param string $description Detailed description of the grievance
 * @return array Result array with success status and message
 */
function create_grievance($category, $district, $description) {
    // Validate inputs
    if (empty($category) || empty($district) || empty($description)) {
        return ['success' => false, 'message' => 'All fields are required'];
    }
    
    // Validate description length (minimum requirement)
    if (strlen($description) < 10) {
        return ['success' => false, 'message' => 'Description must be at least 10 characters'];
    }
    
    // Get database connection
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    // Prepare SQL statement
    $sql = "INSERT INTO grievances (category, district, description, status) VALUES (?, ?, ?, 'pending')";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to prepare statement'];
    }
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "sss", $category, $district, $description);
    
    // Execute statement
    $result = mysqli_stmt_execute($stmt);
    
    if ($result) {
        $grievance_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => true, 'message' => 'Grievance submitted successfully', 'id' => $grievance_id];
    } else {
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to submit grievance'];
    }
}

/**
 * Read all grievances or filter by parameters
 * @param array $filters Optional filters (district, category, status)
 * @return array Result array with grievance records
 */
function read_grievances($filters = []) {
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    // Build query based on filters
    $sql = "SELECT * FROM grievances WHERE 1=1";
    $params = [];
    $types = "";
    
    if (!empty($filters['district'])) {
        $sql .= " AND district = ?";
        $params[] = $filters['district'];
        $types .= "s";
    }
    
    if (!empty($filters['category'])) {
        $sql .= " AND category = ?";
        $params[] = $filters['category'];
        $types .= "s";
    }
    
    if (!empty($filters['status'])) {
        $sql .= " AND status = ?";
        $params[] = $filters['status'];
        $types .= "s";
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to prepare statement'];
    }
    
    // Bind parameters if any
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $grievances = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $grievances[] = $row;
    }
    
    mysqli_stmt_close($stmt);
    close_db_connection($conn);
    
    return ['success' => true, 'data' => $grievances];
}

/**
 * Read a single grievance by ID
 * @param int $id Grievance ID
 * @return array Result array with grievance data
 */
function read_grievance_by_id($id) {
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    $sql = "SELECT * FROM grievances WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to prepare statement'];
    }
    
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $grievance = mysqli_fetch_assoc($result);
    
    mysqli_stmt_close($stmt);
    close_db_connection($conn);
    
    if ($grievance) {
        return ['success' => true, 'data' => $grievance];
    } else {
        return ['success' => false, 'message' => 'Grievance not found'];
    }
}

/**
 * Update grievance status (typically used by admin)
 * @param int $id Grievance ID
 * @param string $status New status (pending, reviewed, resolved)
 * @return array Result array with success status
 */
function update_grievance_status($id, $status) {
    // Validate status value
    $valid_statuses = ['pending', 'reviewed', 'resolved'];
    if (!in_array($status, $valid_statuses)) {
        return ['success' => false, 'message' => 'Invalid status value'];
    }
    
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    $sql = "UPDATE grievances SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to prepare statement'];
    }
    
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
    $result = mysqli_stmt_execute($stmt);
    
    if ($result && mysqli_stmt_affected_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => true, 'message' => 'Grievance status updated successfully'];
    } else {
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Grievance not found or status unchanged'];
    }
}

/**
 * Update a grievance record (full update)
 * @param int $id Grievance ID
 * @param array $data Array of fields to update
 * @return array Result array with success status
 */
function update_grievance($id, $data) {
    if (empty($id)) {
        return ['success' => false, 'message' => 'Grievance ID is required'];
    }
    
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    // Build dynamic update query
    $fields = [];
    $params = [];
    $types = "";
    
    if (isset($data['category'])) {
        $fields[] = "category = ?";
        $params[] = $data['category'];
        $types .= "s";
    }
    
    if (isset($data['district'])) {
        $fields[] = "district = ?";
        $params[] = $data['district'];
        $types .= "s";
    }
    
    if (isset($data['description'])) {
        $fields[] = "description = ?";
        $params[] = $data['description'];
        $types .= "s";
    }
    
    if (isset($data['status'])) {
        $valid_statuses = ['pending', 'reviewed', 'resolved'];
        if (in_array($data['status'], $valid_statuses)) {
            $fields[] = "status = ?";
            $params[] = $data['status'];
            $types .= "s";
        }
    }
    
    if (empty($fields)) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'No fields to update'];
    }
    
    // Add ID to params
    $params[] = $id;
    $types .= "i";
    
    $sql = "UPDATE grievances SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to prepare statement'];
    }
    
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    $result = mysqli_stmt_execute($stmt);
    
    if ($result) {
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => true, 'message' => 'Grievance updated successfully'];
    } else {
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to update grievance'];
    }
}

/**
 * Delete a grievance record
 * @param int $id Grievance ID
 * @return array Result array with success status
 */
function delete_grievance($id) {
    if (empty($id)) {
        return ['success' => false, 'message' => 'Grievance ID is required'];
    }
    
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    $sql = "DELETE FROM grievances WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to prepare statement'];
    }
    
    mysqli_stmt_bind_param($stmt, "i", $id);
    $result = mysqli_stmt_execute($stmt);
    
    if ($result && mysqli_stmt_affected_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => true, 'message' => 'Grievance deleted successfully'];
    } else {
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Grievance not found or already deleted'];
    }
}

/**
 * Get grievance count by district
 * @param string $district District name
 * @return int Grievance count
 */
function get_grievance_count_by_district($district) {
    $conn = get_db_connection();
    if (!$conn) {
        return 0;
    }
    
    $sql = "SELECT COUNT(*) as count FROM grievances WHERE district = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        close_db_connection($conn);
        return 0;
    }
    
    mysqli_stmt_bind_param($stmt, "s", $district);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    mysqli_stmt_close($stmt);
    close_db_connection($conn);
    
    return $row['count'] ?? 0;
}

/**
 * Get grievance statistics by category
 * @return array Statistics array
 */
function get_grievance_statistics() {
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    $sql = "SELECT category, COUNT(*) as count FROM grievances GROUP BY category ORDER BY count DESC";
    $result = mysqli_query($conn, $sql);
    
    $stats = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $stats[] = $row;
    }
    
    close_db_connection($conn);
    
    return ['success' => true, 'data' => $stats];
}

// Handle AJAX requests if called directly
if (basename($_SERVER['PHP_SELF']) == 'grievance_crud.php' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'create':
            $result = create_grievance(
                sanitize_input($_POST['category'] ?? ''),
                sanitize_input($_POST['district'] ?? ''),
                sanitize_input($_POST['description'] ?? '')
            );
            json_response($result['success'], $result['message'], $result['id'] ?? null);
            break;
            
        case 'read':
            $filters = [];
            if (!empty($_POST['district'])) $filters['district'] = sanitize_input($_POST['district']);
            if (!empty($_POST['category'])) $filters['category'] = sanitize_input($_POST['category']);
            if (!empty($_POST['status'])) $filters['status'] = sanitize_input($_POST['status']);
            
            $result = read_grievances($filters);
            json_response($result['success'], '', $result['data'] ?? []);
            break;
            
        case 'update_status':
            $id = intval($_POST['id'] ?? 0);
            $status = sanitize_input($_POST['status'] ?? '');
            $result = update_grievance_status($id, $status);
            json_response($result['success'], $result['message']);
            break;
            
        case 'update':
            $id = intval($_POST['id'] ?? 0);
            $data = [];
            if (!empty($_POST['category'])) $data['category'] = sanitize_input($_POST['category']);
            if (!empty($_POST['district'])) $data['district'] = sanitize_input($_POST['district']);
            if (!empty($_POST['description'])) $data['description'] = sanitize_input($_POST['description']);
            if (!empty($_POST['status'])) $data['status'] = sanitize_input($_POST['status']);
            
            $result = update_grievance($id, $data);
            json_response($result['success'], $result['message']);
            break;
            
        case 'delete':
            $id = intval($_POST['id'] ?? 0);
            $result = delete_grievance($id);
            json_response($result['success'], $result['message']);
            break;
            
        case 'statistics':
            $result = get_grievance_statistics();
            json_response($result['success'], '', $result['data'] ?? []);
            break;
            
        default:
            json_response(false, 'Invalid action');
    }
}
?>
