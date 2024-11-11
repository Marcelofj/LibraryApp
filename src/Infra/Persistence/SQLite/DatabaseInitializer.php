<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Infra\Persistence\SQLite;

use PDO;
use PDOException;

/**
 * Class DatabaseInitializer
 * 
 * This class is responsible for initializing the SQLite database, creating
 * the necessary tables if they do not exist, and managing the database connection.
 * It includes methods for setting up the "books" and "users" tables.
 */
class DatabaseInitializer
{
    private string $databasePath;
    private ?PDO $pdo;

    /**
     * Constructor
     *
     * Initializes the database connection. If no PDO object is passed, 
     * a new connection is created using the SQLite database.
     *
     * @param PDO|null $pdo The PDO connection (optional).
     */
    public function __construct(?PDO $pdo = null)
    {
        // Sets the database file path to the 'library.sqlite3' file in the current directory.
        $this->databasePath = __DIR__ . '/library.sqlite3';

        $this->pdo = $pdo ?: new PDO('sqlite:' . $this->databasePath);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Initialize the database by creating the necessary tables if they do not exist.
     */
    public function initialize(): void
    {
        try {
            $createBooksTable = "
                CREATE TABLE IF NOT EXISTS books (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    title TEXT NOT NULL,
                    author TEXT NOT NULL,
                    isbn TEXT UNIQUE NOT NULL,
                    isAvailable BOOLEAN NOT NULL DEFAULT 1
                );
            ";
            $this->pdo->exec($createBooksTable);
            echo "Table books created.\n";

            $createUsersTable = "
                CREATE TABLE IF NOT EXISTS users (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    email TEXT UNIQUE NOT NULL,
                    role TEXT NOT NULL
                );
            ";
            $this->pdo->exec($createUsersTable);
            echo "Table users created.\n";

            $createStudentsTable = "
                CREATE TABLE IF NOT EXISTS students (
                    user_id INTEGER PRIMARY KEY,
                    grade_level TEXT NOT NULL,
                    course TEXT NOT NULL,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                );
            ";
            $this->pdo->exec($createStudentsTable);
            echo "Table students created.\n";

            $createTeachersTable = "
                CREATE TABLE IF NOT EXISTS teachers (
                    user_id INTEGER PRIMARY KEY,
                    department TEXT NOT NULL,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                );
            ";
            $this->pdo->exec($createTeachersTable);
            echo "Table teachers created.\n";

            $createBookLoansTable = "
                CREATE TABLE book_loans (
                    id INTEGER PRIMARY KEY,
                    book_id INTEGER NOT NULL,
                    user_id INTEGER NOT NULL,
                    loan_date DATETIME NOT NULL,
                    due_date DATETIME NOT NULL,
                    return_date DATETIME DEFAULT NULL,
                    status TEXT NOT NULL DEFAULT active,
                    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                );        
            ";

            $this->pdo->exec($createBookLoansTable);
            echo "Table book_loans created.\n";
        } catch (PDOException $e) {
            echo "Error initializing the database: " . $e->getMessage() . "\n";
            exit(1);
        }
    }
}

// Run the initializer if this script is run via the command line interface (CLI)
if (php_sapi_name() == 'cli') {
    (new DatabaseInitializer())->initialize();
}
