<?php
require_once '../includes/db.php';

header("Content-Type: application/json");

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

// Define your JWT secret key
define('JWT_SECRET', 'ghfkffu6378382826hhdjgk'); // Replace 'your_secret_key' with a strong secret key

// Authenticate user and generate JWT token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $username = $data->username ?? '';
    $password = $data->password ?? '';
    $company_id = $data->company_id ?? ''; // Added company_id input

    // Validate company_id in the Companies table
    $companyStmt = $conn->prepare("SELECT * FROM companies WHERE id = ?");
    $companyStmt->bind_param("s", $company_id);
    $companyStmt->execute();
    $companyResult = $companyStmt->get_result();

    if ($companyResult->num_rows === 1) {
        // Company exists; proceed to validate user credentials
        $userStmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND password = ? AND company_id = ?");
        $userStmt->bind_param("sss", $username, $password, $company_id);
        $userStmt->execute();
        $userResult = $userStmt->get_result();

        if ($userResult->num_rows === 1) {
            $user = $userResult->fetch_assoc();

            // JWT Header
            $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
            $base64UrlHeader = base64url_encode($header);

            // JWT Payload
            $payload = json_encode([
                "iss" => "invest.io",
                "aud" => "invest.io",
                "iat" => time(),
                "exp" => time() + 3600, // Token valid for 1 hour
                "sub" => $user['id'],
                "company_id" => $company_id
            ]);
            $base64UrlPayload = base64url_encode($payload);

            // JWT Signature
            $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, JWT_SECRET, true);
            $base64UrlSignature = base64url_encode($signature);

            // Combine parts to form JWT
            $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

            echo json_encode(["status" => "success", "token" => $jwt]);
        } else {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "Invalid user credentials"]);
        }
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Invalid company ID"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
