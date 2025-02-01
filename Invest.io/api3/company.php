<?php
require_once '../includes/db.php';

header("Content-Type: application/json");

// Helper functions for base64 URL encoding/decoding
function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}

// Function to validate JWT
function validate_jwt($jwt) {
    list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $jwt);
    
    $header = base64url_decode($base64UrlHeader);
    $payload = base64url_decode($base64UrlPayload);
    $signature = base64url_decode($base64UrlSignature);

    $jwt_secret = 'ghfkffu6378382826hhdjgk'; // Use your JWT secret key
    $dataToVerify = $base64UrlHeader . "." . $base64UrlPayload;
    
    // Verify the signature
    $expectedSignature = hash_hmac('sha256', $dataToVerify, $jwt_secret, true);
    if ($signature !== $expectedSignature) {
        error_log('JWT signature mismatch');
        return false;
    }

    // Decode the payload to check expiration
    $decodedPayload = json_decode($payload, true);
    if (time() > $decodedPayload['exp']) {
        error_log('JWT token expired');
        return false;
    }

    return $decodedPayload;
}

// Debugging: Log all headers to check if Authorization is present
error_log(print_r(getallheaders(), true));

$headers = getallheaders();
if (isset($headers['Authorization'])) {
    $authHeader = $headers['Authorization'];
    
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $jwt = $matches[1]; // Extract the JWT token

        // Validate the JWT token
        $userData = validate_jwt($jwt);

        if ($userData) {
            // Token is valid, proceed with fetching the company details
            if (isset($_GET['id'])) {
                $companyId = $_GET['id'];
                
                // Fetch company details
                $query = "SELECT * FROM companies WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $companyId);
                $stmt->execute();
                $companyResult = $stmt->get_result();
                
                if ($companyResult->num_rows > 0) {
                    $company = $companyResult->fetch_assoc();

                    // Fetch additional data from other tables
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
                    
                    // Return combined data as JSON
                    echo json_encode([
                        'company' => $company,
                        'data' => $data
                    ]);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Company not found']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid company ID']);
            }
        } else {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "Invalid or expired token"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Authorization token not provided"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Authorization header missing"]);
}
?>
