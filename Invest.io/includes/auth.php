<?php
require_once '../includes/db.php';

function base64url_decode($data) {
    return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
}

function authenticate() {
    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authHeader);

        // Split the JWT into its parts
        $parts = explode('.', $token);
        if (count($parts) === 3) {
            [$header, $payload, $signature] = $parts;

            // Decode header and payload
            $decodedHeader = json_decode(base64url_decode($header), true);
            $decodedPayload = json_decode(base64url_decode($payload), true);

            // Verify signature
            $validSignature = hash_hmac('sha256', $header . "." . $payload, JWT_SECRET, true);
            $validSignature = base64url_encode($validSignature);

            if ($signature === $validSignature) {
                // Check expiration
                if (isset($decodedPayload['exp']) && $decodedPayload['exp'] > time()) {
                    return $decodedPayload; // Return decoded payload if valid
                } else {
                    http_response_code(401);
                    echo json_encode(["status" => "error", "message" => "Token expired"]);
                    exit;
                }
            }
        }
    }
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Invalid token"]);
    exit;
}
?>
