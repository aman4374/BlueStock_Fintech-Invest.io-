<?php
require_once 'includes/db.php';

// Get the request URI
$requestUri = trim($_SERVER['REQUEST_URI'], '/');

// Handle routing based on the request URI
if ($requestUri === '' || $requestUri === 'index.php') {
    require 'index.php'; // Load landing page
} elseif (preg_match('/^company\/(\d+)$/', $requestUri, $matches)) {
    $_GET['id'] = $matches[1]; // Capture company ID from URL
    require 'pages/company.php'; // Load company details page
} else {
    // 404: Page Not Found
    http_response_code(404);
    echo "404 Not Found";
}
?>
