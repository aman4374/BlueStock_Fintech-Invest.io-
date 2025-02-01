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

// Get company ID from query parameter
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $companyId = $_GET['id'];
    
    // Fetch company details
    $query = "SELECT * FROM companies WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $companyId);
    $stmt->execute();
    $companyResult = $stmt->get_result();
    
    if ($companyResult->num_rows > 0) {
        $company = $companyResult->fetch_assoc();
        
        // Fetch additional data from other tables (cashflow, balancesheet, profit and loss, ratios)
        $tables = ['cashflow', 'prosandcons', 'balancesheet', 'profitandloss', 'analysis', 'documents'];
        $data = [];
        
        foreach ($tables as $table) {
            $query = "SELECT * FROM $table WHERE company_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $companyId);
            $stmt->execute();
            $tableResult = $stmt->get_result();
            $data[$table] = $tableResult->fetch_all(MYSQLI_ASSOC);
        }
        
        // Combine the company data and additional data and return as JSON
        echo json_encode([
            'company' => $company,
            'data' => $data
        ]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'Company not found']);
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid company ID']);
}
?>
