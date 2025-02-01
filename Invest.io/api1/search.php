<?php
require_once '../includes/db.php';

// Check if the 'id' parameter is set
if (isset($_GET['id'])) {
    $searchTerm = $_GET['id'];

    if (!empty($searchTerm)) {
        // Sanitize the input to prevent SQL injection
        $searchTerm = "%" . $conn->real_escape_string($searchTerm) . "%";

        // Query to search for companies by name or ID
        $query = "SELECT id, company_name, company_logo, about_company, website, nse_profile, bse_profile, chart_link 
                  FROM companies 
                  WHERE company_name LIKE ? OR id LIKE ? 
                  LIMIT 10";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
    } else {
        // Query to fetch all companies if no search term is provided
        $query = "SELECT id, company_name, company_logo, about_company, website, nse_profile, bse_profile, chart_link, face_value,book_value,roce_percentage,roe_percentage 
                  FROM companies";
        $stmt = $conn->prepare($query);
    }

    // Execute the query
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
        echo json_encode(['error' => 'No companies found']);
    }
} else {
    echo json_encode(['error' => 'No search term provided']);
}
?>  
