    -- Create the database if it does not exist
    CREATE DATABASE IF NOT EXISTS LibraryDB;
    USE LibraryDB;

    -- Create a 'books' table
    CREATE TABLE IF NOT EXISTS books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        genre VARCHAR(100) NOT NULL,
        user_id INT,
    );

    -- Create a 'users' table
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        access_token VARCHAR(255)
    );

    -- Optionally insert some initial data
    INSERT INTO books (title, author, genre) VALUES
    ('1984', 'George Orwell', 'Dystopian'),
    ('To Kill a Mockingbird', 'Harper Lee', 'Novel'),
    ('The Great Gatsby', 'F. Scott Fitzgerald', 'Novel');

    -- Ensure user privileges are correct
    GRANT ALL PRIVILEGES ON LibraryDB.* TO 'user'@'%' IDENTIFIED BY 'password';
    FLUSH PRIVILEGES;
