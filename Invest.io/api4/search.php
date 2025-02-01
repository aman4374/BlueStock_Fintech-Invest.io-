<?php
require_once '../includes/db.php';

// Define the static API key if not already defined
if (!defined('API_SECRET_KEY')) {
    define('API_SECRET_KEY', 'ghfkffu6378382826hhdjgk');
}

// Check for API key in the request
if (!isset($_GET['api_key']) || $_GET['api_key'] !== API_SECRET_KEY) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Incorrect or missing API key.']);
    exit;
}
// Check if the 'id' parameter is set
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $searchTerm = $_GET['id'];

    // Sanitize the input to prevent SQL injection
    $searchTerm = "%" . $conn->real_escape_string($searchTerm) . "%";
    
    // Query to search for companies by name
    $query = "SELECT id, company_name FROM companies WHERE company_name LIKE ? OR id LIKE ? LIMIT 10";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch results and send them as JSON response
    $companies = [];
    while ($row = $result->fetch_assoc()) {
        $companies[] = $row;
    }
    
    if ($companies) {
        echo json_encode(['companies' => $companies]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'No companies found']);
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'No search term provided']);
}
?>
