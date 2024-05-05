<?php
include 'db.php';
header('Content-Type: application/json');  // Set header for JSON response

try {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Function to check if credentials are valid
    function checkCredentials($username, $password, $pdo)
    {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        // get from mysql users pdo
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        // compare password
        if ($user && password_verify($password, $user['password'])) {
            return true;
        }
        return false;
    }

    if (checkCredentials($username, $password, $pdo)) {
        $token = bin2hex(random_bytes(32));
        // save access token to database
        $stmt = $pdo->prepare("UPDATE users SET access_token = ? WHERE username = ?");
        $stmt->execute([$token, $username]);
        echo json_encode(['success' => true, 'token' => $token]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
