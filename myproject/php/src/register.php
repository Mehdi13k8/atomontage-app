<?php
session_start();
include 'db.php';
$host = 'mysql';
$dbname = 'LibraryDB';
$user = 'user';
$password = 'password';
$dsn = "mysql:host=$host;dbname=$dbname";

$pdo = new PDO($dsn, $user, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to prevent SQL injection
    $stmt =  $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    // Bind the parameters
    $stmt->bindParam(1, $username, PDO::PARAM_STR);
    $stmt->bindParam(2, $email, PDO::PARAM_STR);
    $stmt->bindParam(3, $hashed_password, PDO::PARAM_STR);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "<p>Registration successful. You can now <a href='login.html'>login</a>.</p>";
    } else {
        echo "<p>Error: " . $stmt->errorInfo()[2] . "</p>";
    }

}
