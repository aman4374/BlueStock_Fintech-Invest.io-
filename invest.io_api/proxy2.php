<?php
// proxy.php

// Include the config file
include('config2.php');

// Get the search term from the request (sent from the frontend)
$searchTerm = isset($_GET['id']) ? $_GET['id'] : '';

// Log the search term for debugging purposes (you can check your server logs)
error_log("Search term: " . $searchTerm);

// Prepare the full API URL with the API key
$apiUrl = API_URL . '?id=' . urlencode($searchTerm) . '&api_key=' . API_KEY;

// Fetch data from the API
$response = file_get_contents($apiUrl);

// Log the response from the API for debugging purposes
error_log("API Response: " . $response);

// Return the response back to the frontend
header('Content-Type: application/json');
echo $response;
?>
