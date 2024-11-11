<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Infra\Persistence\SQLite;

use PDO;
use PDOException;

/**
 * Database class
 *
 * Singleton class responsible for creating and managing the SQLite database connection.
 * It ensures a single instance of the database connection throughout the application.
 */
class Database
{
    /**
     * @var Database|null $instance The singleton instance of the Database class.
     */
    private static ?Database $instance = null;

    /**
     * @var PDO $connection The PDO connection to the SQLite database.
     */
    private PDO $connection;

    /**
     * @var string $path The path to the SQLite database file.
     */
    private string $path =  __DIR__ . '/library.sqlite3';

    /**
     * Constructor
     *
     * This is a private constructor that ensures the database connection is created only once
     * by calling the connect() method to establish the SQLite connection.
     */
    private function __construct()
    {
        $this->connect();
    }

    /**
     * Get the singleton instance of the Database class.
     *
     * This method ensures only one instance of the Database class exists throughout the application.
     * It returns the same instance every time it is called.
     *
     * @return Database The singleton instance of the Database class.
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Establish a connection to the SQLite database.
     *
     * This private method attempts to create a PDO connection to the SQLite database.
     * If the connection fails, an exception will be thrown and the error message will be displayed.
     */
    private function connect(): void
    {
        try {
            $this->connection = new PDO('sqlite:' . $this->path);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $this->path . PHP_EOL;
            echo "Connection Error: " . $e->getMessage();
        }
    }

    /**
     * Get the PDO connection.
     *
     * This method returns the current PDO connection to the SQLite database.
     *
     * @return PDO The PDO instance representing the connection to the SQLite database.
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Prevent cloning of the Database instance.
     *
     * This method ensures that the Database class cannot be cloned, maintaining
     * the singleton pattern.
     */
    public function __clone()
    {
        throw new \LogicException('Cloning of this object is not allowed.');
    }


    /**
     * Prevent unserializing the Database instance.
     *
     * This method ensures that the Database class cannot be unserialized, maintaining
     * the singleton pattern.
     */
    public function __wakeup()
    {
        throw new \LogicException('Unserializing of this object is not allowed.');
    }
}
