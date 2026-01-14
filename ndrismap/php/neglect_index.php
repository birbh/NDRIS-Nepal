<?php
/**
 * Urban Neglect Index Calculator
 * NDRIS-Nepal System
 * 
 * Computes a heuristic neglect score for each district based on:
 * - Number of grievances (citizen complaints)
 * - Number of disasters (vulnerability)
 * - Average policy effectiveness score (governance response)
 * 
 * Formula: Neglect Score = (Grievances * 0.4) + (Disasters * 0.3) - (Policy Score * 0.3)
 * Higher score = Higher neglect
 * 
 * Note: This is a simplified heuristic model for educational purposes
 */

require_once 'db.php';
require_once 'disaster_crud.php';
require_once 'grievance_crud.php';
require_once 'policy_crud.php';

/**
 * Calculate neglect score for a specific district
 * @param string $district District name
 * @return array Result with calculated metrics
 */
function calculate_district_neglect_score($district) {
    // Get counts from each module
    $grievance_count = get_grievance_count_by_district($district);
    $disaster_count = get_disaster_count_by_district($district);
    $policy_score = get_average_policy_score_by_district($district);
    
    // Compute neglect score using weighted formula
    // Higher grievances and disasters increase score
    // Higher policy effectiveness decreases score
    $neglect_score = ($grievance_count * 0.4) + ($disaster_count * 0.3) - ($policy_score * 0.3);
    
    // Ensure minimum score is 0
    $neglect_score = max(0, $neglect_score);
    
    return [
        'district' => $district,
        'grievance_count' => $grievance_count,
        'disaster_count' => $disaster_count,
        'policy_score' => $policy_score,
        'neglect_score' => round($neglect_score, 2)
    ];
}

/**
 * Update neglect index table with calculated score
 * @param string $district District name
 * @return array Result array with success status
 */
function update_neglect_index($district) {
    // Calculate metrics
    $metrics = calculate_district_neglect_score($district);
    
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    // Use INSERT ... ON DUPLICATE KEY UPDATE for upsert operation
    $sql = "INSERT INTO neglect_index (district, grievance_count, disaster_count, policy_score, neglect_score) 
            VALUES (?, ?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE 
                grievance_count = VALUES(grievance_count),
                disaster_count = VALUES(disaster_count),
                policy_score = VALUES(policy_score),
                neglect_score = VALUES(neglect_score),
                last_updated = CURRENT_TIMESTAMP";
    
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to prepare statement'];
    }
    
    mysqli_stmt_bind_param($stmt, "siiid", 
        $metrics['district'],
        $metrics['grievance_count'],
        $metrics['disaster_count'],
        $metrics['policy_score'],
        $metrics['neglect_score']
    );
    
    $result = mysqli_stmt_execute($stmt);
    
    if ($result) {
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return [
            'success' => true, 
            'message' => 'Neglect index updated successfully',
            'data' => $metrics
        ];
    } else {
        mysqli_stmt_close($stmt);
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to update neglect index'];
    }
}

/**
 * Get neglect index data for a specific district
 * @param string $district District name
 * @return array Result with neglect index data
 */
function get_neglect_index($district) {
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    $sql = "SELECT * FROM neglect_index WHERE district = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        close_db_connection($conn);
        return ['success' => false, 'message' => 'Failed to prepare statement'];
    }
    
    mysqli_stmt_bind_param($stmt, "s", $district);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $data = mysqli_fetch_assoc($result);
    
    mysqli_stmt_close($stmt);
    close_db_connection($conn);
    
    if ($data) {
        return ['success' => true, 'data' => $data];
    } else {
        // If not found, calculate and store it
        $calc_result = update_neglect_index($district);
        return $calc_result;
    }
}

/**
 * Get all neglect indices (all districts)
 * @return array Result with all district neglect data
 */
function get_all_neglect_indices() {
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    $sql = "SELECT * FROM neglect_index ORDER BY neglect_score DESC";
    $result = mysqli_query($conn, $sql);
    
    $indices = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $indices[] = $row;
    }
    
    close_db_connection($conn);
    
    return ['success' => true, 'data' => $indices];
}

/**
 * Recalculate all district neglect indices
 * Useful after bulk data updates
 * @return array Result with update count
 */
function recalculate_all_indices() {
    // Get list of unique districts from all tables
    $conn = get_db_connection();
    if (!$conn) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }
    
    // Union query to get all unique districts
    $sql = "SELECT DISTINCT district FROM disasters
            UNION
            SELECT DISTINCT district FROM grievances
            UNION
            SELECT DISTINCT district FROM policies
            ORDER BY district";
    
    $result = mysqli_query($conn, $sql);
    
    $districts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $districts[] = $row['district'];
    }
    
    close_db_connection($conn);
    
    // Update index for each district
    $updated_count = 0;
    foreach ($districts as $district) {
        $update_result = update_neglect_index($district);
        if ($update_result['success']) {
            $updated_count++;
        }
    }
    
    return [
        'success' => true,
        'message' => "Recalculated indices for $updated_count districts",
        'count' => $updated_count
    ];
}

/**
 * Get neglect level category based on score
 * @param float $score Neglect score
 * @return string Level category (low, medium, high)
 */
function get_neglect_level($score) {
    if ($score < 5) {
        return 'low';
    } elseif ($score < 10) {
        return 'medium';
    } else {
        return 'high';
    }
}

/**
 * Get color code for map visualization
 * @param float $score Neglect score
 * @return string Hex color code
 */
function get_neglect_color($score) {
    if ($score < 5) {
        return '#4CAF50'; // Green - low neglect
    } elseif ($score < 10) {
        return '#FFC107'; // Yellow - medium neglect
    } else {
        return '#F44336'; // Red - high neglect
    }
}

/**
 * Get district summary with all metrics
 * @param string $district District name
 * @return array Comprehensive district data
 */
function get_district_summary($district) {
    // Get neglect index
    $neglect_result = get_neglect_index($district);
    
    if (!$neglect_result['success']) {
        return $neglect_result;
    }
    
    $data = $neglect_result['data'];
    $score = $data['neglect_score'];
    
    // Add additional context
    $data['neglect_level'] = get_neglect_level($score);
    $data['color_code'] = get_neglect_color($score);
    
    return ['success' => true, 'data' => $data];
}

// Handle AJAX requests if called directly
if (basename($_SERVER['PHP_SELF']) == 'neglect_index.php' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'get_index':
            $district = sanitize_input($_POST['district'] ?? '');
            if (empty($district)) {
                json_response(false, 'District name is required');
            }
            $result = get_neglect_index($district);
            json_response($result['success'], $result['message'] ?? '', $result['data'] ?? null);
            break;
            
        case 'get_all':
            $result = get_all_neglect_indices();
            json_response($result['success'], '', $result['data'] ?? []);
            break;
            
        case 'update_index':
            $district = sanitize_input($_POST['district'] ?? '');
            if (empty($district)) {
                json_response(false, 'District name is required');
            }
            $result = update_neglect_index($district);
            json_response($result['success'], $result['message'], $result['data'] ?? null);
            break;
            
        case 'recalculate_all':
            $result = recalculate_all_indices();
            json_response($result['success'], $result['message'], ['count' => $result['count'] ?? 0]);
            break;
            
        case 'district_summary':
            $district = sanitize_input($_POST['district'] ?? '');
            if (empty($district)) {
                json_response(false, 'District name is required');
            }
            $result = get_district_summary($district);
            json_response($result['success'], '', $result['data'] ?? null);
            break;
            
        default:
            json_response(false, 'Invalid action');
    }
}

// Handle GET requests for simple queries
if (basename($_SERVER['PHP_SELF']) == 'neglect_index.php' && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['district'])) {
    $district = sanitize_input($_GET['district']);
    $result = get_district_summary($district);
    json_response($result['success'], '', $result['data'] ?? null);
}
?>
