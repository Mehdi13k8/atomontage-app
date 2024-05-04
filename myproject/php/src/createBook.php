<?php
include 'db.php'; // Make sure this includes your database connection settings

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['title'], $data['author'], $data['genre'])) {
    $title = $data['title'];
    $author = $data['author'];
    $genre = $data['genre'];

    // Prepare SQL Statement
    $stmt = $pdo->prepare("INSERT INTO books (title, author, genre) VALUES (?, ?, ?)");
    if ($stmt->execute([$title, $author, $genre])) {
        echo json_encode(['success' => true, 'message' => 'Book added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add book']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Incomplete book data']);
}
?>
