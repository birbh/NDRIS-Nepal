<?php
/**
 * Disaster CRUD Module
 * NDRIS-Nepal System
 * 
 * Handles Create, Read, Update, Delete operations for disaster records
 * Uses MySQLi prepared statements for security
 */

require_once 'db.php';

/**
 * Create a new disaster record
 * @param string $title Disaster title
 * @param string $type Disaster type (earthquake, flood, etc.)
 * @param string $district Affected district
 * @param int $year Year of occurrence
 * @param int $impact_level Impact level (1-10)
 * @param string $description Detailed description
 * @return array Result array with success status and message
 */
function create_disaster($title, $type, $district, $year, $impact_level, $description) {
    // Validate inputs
    if (empty($title) || empty($type) || empty($district) || empty($year) || empty($impact_level)) {
        return ['success' => false, 'message' => 'All required fields must be filled'];
    }
    
    // Validate impact level range
    if ($impact_level < 1 || $impact_level > 10) {
        return ['success' => false, 'message' => 'Impact level must be between 1 and 10'];
    }
    
    // Validate year
    if ($year < 1900 || $year > date('Y')) {
        return ['success' => false, 'message' => 'Invalid year'];
    }
    
    // Get database connection
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    // Prepare SQL statement
    $sql = "INSERT INTO disasters (title, type, district, year, impact_level, description) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to prepare statement'];
    }
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ssssis", $title, $type, $district, $year, $impact_level, $description);
    
    // Execute statement
    $result = mysqli_stmt_execute($stmt);
    
    if ($result) {
        $disaster_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => true, 'message' => 'Disaster record created successfully', 'id' => $disaster_id];
    } else {
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to create disaster record'];
    }
}

/**
 * Read all disaster records or filter by parameters
 * @param array $filters Optional filters (district, year, type)
 * @return array Result array with disaster records
 */
function read_disasters($filters = []) {
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    // Build query based on filters
    $sql = "SELECT * FROM disasters WHERE 1=1";
    $params = [];
    $types = "";
    
    if (!empty($filters['district'])) {
        $sql .= " AND district = ?";
        $params[] = $filters['district'];
        $types .= "s";
    }
    
    if (!empty($filters['year'])) {
        $sql .= " AND year = ?";
        $params[] = $filters['year'];
        $types .= "i";
    }
    
    if (!empty($filters['type'])) {
        $sql .= " AND type = ?";
        $params[] = $filters['type'];
        $types .= "s";
    }
    
    $sql .= " ORDER BY year DESC, created_at DESC";
    
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
    
    $disasters = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $disasters[] = $row;
    }
    
    mysqli_stmt_close($stmt);
    close_db_connection($conn);
    
    return ['success' => true, 'data' => $disasters];
}

/**
 * Read a single disaster record by ID
 * @param int $id Disaster ID
 * @return array Result array with disaster data
 */
function read_disaster_by_id($id) {
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    $sql = "SELECT * FROM disasters WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to prepare statement'];
    }
    
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $disaster = mysqli_fetch_assoc($result);
    
    mysqli_stmt_close($stmt);
    close_db_connection($conn);
    
    if ($disaster) {
        return ['success' => true, 'data' => $disaster];
    } else {
        return ['success' => false, 'message' => 'Disaster not found'];
    }
}

/**
 * Update an existing disaster record
 * @param int $id Disaster ID
 * @param array $data Array of fields to update
 * @return array Result array with success status
 */
function update_disaster($id, $data) {
    // Validate required ID
    if (empty($id)) {
        return ['success' => false, 'message' => 'Disaster ID is required'];
    }
    
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    // Build dynamic update query
    $fields = [];
    $params = [];
    $types = "";
    
    if (isset($data['title'])) {
        $fields[] = "title = ?";
        $params[] = $data['title'];
        $types .= "s";
    }
    
    if (isset($data['type'])) {
        $fields[] = "type = ?";
        $params[] = $data['type'];
        $types .= "s";
    }
    
    if (isset($data['district'])) {
        $fields[] = "district = ?";
        $params[] = $data['district'];
        $types .= "s";
    }
    
    if (isset($data['year'])) {
        $fields[] = "year = ?";
        $params[] = $data['year'];
        $types .= "i";
    }
    
    if (isset($data['impact_level'])) {
        $fields[] = "impact_level = ?";
        $params[] = $data['impact_level'];
        $types .= "i";
    }
    
    if (isset($data['description'])) {
        $fields[] = "description = ?";
        $params[] = $data['description'];
        $types .= "s";
    }
    
    if (empty($fields)) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'No fields to update'];
    }
    
    // Add ID to params
    $params[] = $id;
    $types .= "i";
    
    $sql = "UPDATE disasters SET " . implode(", ", $fields) . " WHERE id = ?";
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
        return ['success' => true, 'message' => 'Disaster record updated successfully'];
    } else {
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to update disaster record'];
    }
}

/**
 * Delete a disaster record
 * @param int $id Disaster ID
 * @return array Result array with success status
 */
function delete_disaster($id) {
    if (empty($id)) {
        return ['success' => false, 'message' => 'Disaster ID is required'];
    }
    
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    $sql = "DELETE FROM disasters WHERE id = ?";
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
        return ['success' => true, 'message' => 'Disaster record deleted successfully'];
    } else {
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Disaster not found or already deleted'];
    }
}

/**
 * Get disaster count by district
 * @param string $district District name
 * @return int Disaster count
 */
function get_disaster_count_by_district($district) {
    $conn = get_db_connection();
    if (!$conn) {
        return 0;
    }
    
    $sql = "SELECT COUNT(*) as count FROM disasters WHERE district = ?";
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

// Handle AJAX requests if called directly
if (basename($_SERVER['PHP_SELF']) == 'disaster_crud.php' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'create':
            $result = create_disaster(
                sanitize_input($_POST['title'] ?? ''),
                sanitize_input($_POST['type'] ?? ''),
                sanitize_input($_POST['district'] ?? ''),
                intval($_POST['year'] ?? 0),
                intval($_POST['impact_level'] ?? 0),
                sanitize_input($_POST['description'] ?? '')
            );
            json_response($result['success'], $result['message'], $result['id'] ?? null);
            break;
            
        case 'read':
            $filters = [];
            if (!empty($_POST['district'])) $filters['district'] = sanitize_input($_POST['district']);
            if (!empty($_POST['year'])) $filters['year'] = intval($_POST['year']);
            if (!empty($_POST['type'])) $filters['type'] = sanitize_input($_POST['type']);
            
            $result = read_disasters($filters);
            json_response($result['success'], '', $result['data'] ?? []);
            break;
            
        case 'update':
            $id = intval($_POST['id'] ?? 0);
            $data = [];
            if (!empty($_POST['title'])) $data['title'] = sanitize_input($_POST['title']);
            if (!empty($_POST['type'])) $data['type'] = sanitize_input($_POST['type']);
            if (!empty($_POST['district'])) $data['district'] = sanitize_input($_POST['district']);
            if (!empty($_POST['year'])) $data['year'] = intval($_POST['year']);
            if (!empty($_POST['impact_level'])) $data['impact_level'] = intval($_POST['impact_level']);
            if (isset($_POST['description'])) $data['description'] = sanitize_input($_POST['description']);
            
            $result = update_disaster($id, $data);
            json_response($result['success'], $result['message']);
            break;
            
        case 'delete':
            $id = intval($_POST['id'] ?? 0);
            $result = delete_disaster($id);
            json_response($result['success'], $result['message']);
            break;
            
        default:
            json_response(false, 'Invalid action');
    }
}
?>
