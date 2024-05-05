<?php
include 'db.php';  // Ensure your database connection settings are included

// Function to get token from headers
function getTokenFromHeader() {
    $headers = getallheaders();
    $header = $headers['Authorization'] ?? ''; // Check for Authorization header
    return $header;
}

$token = getTokenFromHeader();

if ($token) {
    // Prepare a statement to find the user with the given token
    $stmt = $pdo->prepare("SELECT id FROM users WHERE access_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // User found, proceed to invalidate the token
        $updateStmt = $pdo->prepare("UPDATE users SET access_token = NULL WHERE id = ?");
        $updateStmt->execute([$user['id']]);

        // Optionally, clear any PHP session data if used
        if (session_status() == PHP_SESSION_ACTIVE) {
            $_SESSION = array();  // Clear session variables
            session_destroy();    // Destroy the session
        }

        echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid or expired token']);
    }
} else {
    echo json_encode(['success' => false, 'message' => $headers]);
}
?>
