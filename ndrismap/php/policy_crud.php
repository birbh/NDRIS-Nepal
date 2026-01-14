<?php
/**
 * Policy CRUD Module
 * NDRIS-Nepal System
 * 
 * Handles Create, Read, Update, Delete operations for policy records
 * Uses MySQLi prepared statements for security
 */

require_once 'db.php';

/**
 * Create a new policy record
 * @param string $policy_name Name of the policy
 * @param string $sector Sector (health, education, infrastructure, etc.)
 * @param string $district District where policy is implemented
 * @param int $effectiveness_score Effectiveness score (1-10)
 * @param string $notes Additional notes
 * @return array Result array with success status and message
 */
function create_policy($policy_name, $sector, $district, $effectiveness_score, $notes = '') {
    // Validate inputs
    if (empty($policy_name) || empty($sector) || empty($district) || empty($effectiveness_score)) {
        return ['success' => false, 'message' => 'All required fields must be filled'];
    }
    
    // Validate effectiveness score range
    if ($effectiveness_score < 1 || $effectiveness_score > 10) {
        return ['success' => false, 'message' => 'Effectiveness score must be between 1 and 10'];
    }
    
    // Get database connection
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    // Prepare SQL statement
    $sql = "INSERT INTO policies (policy_name, sector, district, effectiveness_score, notes) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to prepare statement'];
    }
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "sssis", $policy_name, $sector, $district, $effectiveness_score, $notes);
    
    // Execute statement
    $result = mysqli_stmt_execute($stmt);
    
    if ($result) {
        $policy_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => true, 'message' => 'Policy record created successfully', 'id' => $policy_id];
    } else {
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to create policy record'];
    }
}

/**
 * Read all policies or filter by parameters
 * @param array $filters Optional filters (district, sector)
 * @return array Result array with policy records
 */
function read_policies($filters = []) {
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    // Build query based on filters
    $sql = "SELECT * FROM policies WHERE 1=1";
    $params = [];
    $types = "";
    
    if (!empty($filters['district'])) {
        $sql .= " AND district = ?";
        $params[] = $filters['district'];
        $types .= "s";
    }
    
    if (!empty($filters['sector'])) {
        $sql .= " AND sector = ?";
        $params[] = $filters['sector'];
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
    
    $policies = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $policies[] = $row;
    }
    
    mysqli_stmt_close($stmt);
    close_db_connection($conn);
    
    return ['success' => true, 'data' => $policies];
}

/**
 * Read a single policy by ID
 * @param int $id Policy ID
 * @return array Result array with policy data
 */
function read_policy_by_id($id) {
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    $sql = "SELECT * FROM policies WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to prepare statement'];
    }
    
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $policy = mysqli_fetch_assoc($result);
    
    mysqli_stmt_close($stmt);
    close_db_connection($conn);
    
    if ($policy) {
        return ['success' => true, 'data' => $policy];
    } else {
        return ['success' => false, 'message' => 'Policy not found'];
    }
}

/**
 * Update an existing policy record
 * @param int $id Policy ID
 * @param array $data Array of fields to update
 * @return array Result array with success status
 */
function update_policy($id, $data) {
    if (empty($id)) {
        return ['success' => false, 'message' => 'Policy ID is required'];
    }
    
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    // Build dynamic update query
    $fields = [];
    $params = [];
    $types = "";
    
    if (isset($data['policy_name'])) {
        $fields[] = "policy_name = ?";
        $params[] = $data['policy_name'];
        $types .= "s";
    }
    
    if (isset($data['sector'])) {
        $fields[] = "sector = ?";
        $params[] = $data['sector'];
        $types .= "s";
    }
    
    if (isset($data['district'])) {
        $fields[] = "district = ?";
        $params[] = $data['district'];
        $types .= "s";
    }
    
    if (isset($data['effectiveness_score'])) {
        // Validate score range
        if ($data['effectiveness_score'] >= 1 && $data['effectiveness_score'] <= 10) {
            $fields[] = "effectiveness_score = ?";
            $params[] = $data['effectiveness_score'];
            $types .= "i";
        }
    }
    
    if (isset($data['notes'])) {
        $fields[] = "notes = ?";
        $params[] = $data['notes'];
        $types .= "s";
    }
    
    if (empty($fields)) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'No fields to update'];
    }
    
    // Add ID to params
    $params[] = $id;
    $types .= "i";
    
    $sql = "UPDATE policies SET " . implode(", ", $fields) . " WHERE id = ?";
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
        return ['success' => true, 'message' => 'Policy record updated successfully'];
    } else {
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to update policy record'];
    }
}

/**
 * Delete a policy record
 * @param int $id Policy ID
 * @return array Result array with success status
 */
function delete_policy($id) {
    if (empty($id)) {
        return ['success' => false, 'message' => 'Policy ID is required'];
    }
    
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    $sql = "DELETE FROM policies WHERE id = ?";
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
        return ['success' => true, 'message' => 'Policy record deleted successfully'];
    } else {
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Policy not found or already deleted'];
    }
}

/**
 * Get average policy effectiveness score by district
 * @param string $district District name
 * @return float Average policy score
 */
function get_average_policy_score_by_district($district) {
    $conn = get_db_connection();
    if (!$conn) {
        return 0;
    }
    
    $sql = "SELECT AVG(effectiveness_score) as avg_score FROM policies WHERE district = ?";
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
    
    return round($row['avg_score'] ?? 0, 2);
}

/**
 * Get policy count by district
 * @param string $district District name
 * @return int Policy count
 */
function get_policy_count_by_district($district) {
    $conn = get_db_connection();
    if (!$conn) {
        return 0;
    }
    
    $sql = "SELECT COUNT(*) as count FROM policies WHERE district = ?";
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
 * Get policy statistics by sector
 * @return array Statistics array
 */
function get_policy_statistics() {
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    $sql = "SELECT sector, COUNT(*) as count, AVG(effectiveness_score) as avg_score 
            FROM policies GROUP BY sector ORDER BY count DESC";
    $result = mysqli_query($conn, $sql);
    
    $stats = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $row['avg_score'] = round($row['avg_score'], 2);
        $stats[] = $row;
    }
    
    close_db_connection($conn);
    
    return ['success' => true, 'data' => $stats];
}

// Handle AJAX requests if called directly
if (basename($_SERVER['PHP_SELF']) == 'policy_crud.php' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'create':
            $result = create_policy(
                sanitize_input($_POST['policy_name'] ?? ''),
                sanitize_input($_POST['sector'] ?? ''),
                sanitize_input($_POST['district'] ?? ''),
                intval($_POST['effectiveness_score'] ?? 0),
                sanitize_input($_POST['notes'] ?? '')
            );
            json_response($result['success'], $result['message'], $result['id'] ?? null);
            break;
            
        case 'read':
            $filters = [];
            if (!empty($_POST['district'])) $filters['district'] = sanitize_input($_POST['district']);
            if (!empty($_POST['sector'])) $filters['sector'] = sanitize_input($_POST['sector']);
            
            $result = read_policies($filters);
            json_response($result['success'], '', $result['data'] ?? []);
            break;
            
        case 'update':
            $id = intval($_POST['id'] ?? 0);
            $data = [];
            if (!empty($_POST['policy_name'])) $data['policy_name'] = sanitize_input($_POST['policy_name']);
            if (!empty($_POST['sector'])) $data['sector'] = sanitize_input($_POST['sector']);
            if (!empty($_POST['district'])) $data['district'] = sanitize_input($_POST['district']);
            if (!empty($_POST['effectiveness_score'])) $data['effectiveness_score'] = intval($_POST['effectiveness_score']);
            if (isset($_POST['notes'])) $data['notes'] = sanitize_input($_POST['notes']);
            
            $result = update_policy($id, $data);
            json_response($result['success'], $result['message']);
            break;
            
        case 'delete':
            $id = intval($_POST['id'] ?? 0);
            $result = delete_policy($id);
            json_response($result['success'], $result['message']);
            break;
            
        case 'statistics':
            $result = get_policy_statistics();
            json_response($result['success'], '', $result['data'] ?? []);
            break;
            
        default:
            json_response(false, 'Invalid action');
    }
}
?>
