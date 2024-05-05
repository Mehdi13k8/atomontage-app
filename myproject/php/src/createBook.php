<?php
include 'db.php'; // Make sure this includes your database connection settings

$data = json_decode(file_get_contents("php://input"), true);

function getUserIdFromToken($pdo, $token) {
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE access_token = ?");
    $stmt->execute([$token]);
    if ($row = $stmt->fetch()) {
        return $row['user_id'];
    }
    return null;
}

// Assuming the token is sent via an Authorization header
$headers = getallheaders();
$token = isset($headers['Authorization']) ? str_replace("Bearer ", "", $headers['Authorization']) : null;

$user_id = getUserIdFromToken($pdo, $token);

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Authentication required']);
    exit;
}

if (isset($data['title'], $data['author'], $data['genre'])) {
    $title = $data['title'];
    $author = $data['author'];
    $genre = $data['genre'];
    $user_id = getUserIdFromToken($pdo, $token);

    // Prepare SQL Statement
    $stmt = $pdo->prepare("INSERT INTO books (title, author, genre, user_id) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$title, $author, $genre, $user_id])) {
        echo json_encode(['success' => true, 'message' => 'Book added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add book']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Incomplete book data']);
}
?>
