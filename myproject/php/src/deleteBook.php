<?php
include 'db.php';  // Ensure this file contains your database connection setup

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bookId = $_POST['id'];

    if (isset($bookId)) {
        // Prepare and execute the DELETE statement
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
        if ($stmt->execute([$bookId])) {
            echo json_encode(['success' => true, 'message' => 'Book deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete book']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Book ID is required']);
    }
} else {
    // Not a POST request
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
