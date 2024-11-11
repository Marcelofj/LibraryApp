<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Infra\Persistence\SQLite;

/**
 * Class DatabaseDeleter
 * This class provides functionality to delete an SQLite database file.
 * It checks if the file exists and attempts to remove it, providing appropriate messages based on the outcome.
 */
class DatabaseDeleter
{
    /**
     * @var string $databasePath The path to the SQLite database file.
     */
    private string $databasePath;

    /**
     * Constructor for the DatabaseDeleter class.
     * It sets the path of the SQLite database file to be deleted.
     */
    public function __construct()
    {   // Sets the database file path to the 'library.sqlite3' file in the current directory.
        $this->databasePath = __DIR__ . '/library.sqlite3';
    }

    /**
     * Deletes the SQLite database file if it exists.
     * Provides feedback on the operation's success or failure.
     *
     * @return void
     */
    public function delete(): void
    {
        // Checks if the database file exists at the specified path
        if (file_exists($this->databasePath)) {
            if (unlink($this->databasePath)) {
                echo "Database deleted.\n";
            } else {
                echo "Error deleting database.\n";
            }
        } else {
            echo "Database not found.\n";
        }
    }
}

// Runs the deletion process if the script is executed from the command line interface (CLI).
if (php_sapi_name() == 'cli') {
    (new DatabaseDeleter())->delete();
}
