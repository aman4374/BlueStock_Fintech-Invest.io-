<?php
// Load configuration
$config = require './config.php';

// Get the company ID from the query parameters
$companyId = $_GET['id'] ?? null;

if (!$companyId) {
    http_response_code(400);
    echo json_encode(['error' => 'No company ID provided.']);
    exit;
}

// External API URL
$apiUrl = "http://amantrivedi4374.infinityfreeapp.com/api4/company.php?id={$companyId}&api_key={$config['api_key']}";

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL request
$response = curl_exec($ch);

// Check for cURL errors
if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Error fetching company data.']);
    curl_close($ch);
    exit;
}

// Close cURL session
curl_close($ch);

// Forward the response to the frontend
header('Content-Type: application/json');
echo $response;
