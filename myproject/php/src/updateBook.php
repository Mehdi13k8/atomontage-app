<?php
include 'db.php';  // Ensure your database connection settings are included

header('Content-Type: application/json');  // Set header for JSON response

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'], $data['title'], $data['author'], $data['genre'])) {
    $id = $data['id'];
    $title = $data['title'];
    $author = $data['author'];
    $genre = $data['genre'];

    // Prepare SQL Statement to update the book
    $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ?, genre = ? WHERE id = ?");
    if ($stmt->execute([$title, $author, $genre, $id])) {
        echo json_encode(['success' => true, 'message' => 'Book updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update book']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Incomplete book data']);
}
?>
