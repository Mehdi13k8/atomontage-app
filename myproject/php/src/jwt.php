<?php
function base64UrlEncode($data) {
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
}

function jwtHeader() {
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    return base64UrlEncode($header);
}

function jwtPayload($username, $issuedAt, $expiration) {
    $payload = json_encode([
        'iat' => $issuedAt,
        'exp' => $expiration,
        'username' => $username
    ]);
    return base64UrlEncode($payload);
}

function jwtSignature($header, $payload, $secret) {
    $signature = hash_hmac('sha256', "$header.$payload", $secret, true);
    return base64UrlEncode($signature);
}

function createJWT($username, $secret) {
    $issuedAt = time();
    $expiration = $issuedAt + 3600; // valid for 1 hour
    $header = jwtHeader();
    $payload = jwtPayload($username, $issuedAt, $expiration);
    $signature = jwtSignature($header, $payload, $secret);
    
    return "$header.$payload.$signature";
}

function validateJWT($jwt, $secret) {
    // Split the JWT
    $tokenParts = explode('.', $jwt);
    $header = base64UrlEncode(json_decode(base64_decode($tokenParts[0]), true));
    $payload = $tokenParts[1];
    $signatureProvided = $tokenParts[2];

    // Check the expiration time
    $expiration = json_decode(base64_decode($payload), true)['exp'];
    if ($expiration < time()) {
        return false;
    }

    // Build a signature based on the header and payload using the secret
    $signature = jwtSignature($header, $payload, $secret);

    // Compare it to the signature provided in the token
    return ($signature === $signatureProvided);
}
?>
