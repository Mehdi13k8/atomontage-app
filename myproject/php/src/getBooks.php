<?php
include 'db.php';  // Include the database connection file

header('Content-Type: application/json');  // Set header for JSON response

try {
    $stmt = $pdo->query("SELECT id, title, author, genre FROM books");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $books
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
