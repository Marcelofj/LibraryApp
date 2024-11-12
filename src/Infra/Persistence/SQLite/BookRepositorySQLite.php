<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Infra\Persistence\SQLite;

use Marcelofj\LibraryApp\Domain\ValueObjects\ISBN;
use Marcelofj\LibraryApp\Domain\Entities\Book;
use Marcelofj\LibraryApp\Domain\Repositories\BookRepository;
use PDO;

/**
 * BookRepositorySQLite class
 *
 * This class implements the BookRepository interface for interacting with a SQLite database.
 * It provides methods for saving, retrieving, and deleting books from the database.
 */
class BookRepositorySQLite implements BookRepository
{
    private PDO $pdo;

    /**
     * Constructor
     *
     * Initializes a PDO connection to the SQLite database.
     */
    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Save a book to the database
     *
     * This method inserts a new book into the books table in the database.
     * 
     * @param Book $book The book entity to save.
     * 
     * @return void
     */
    public function save(Book $book): void
    {
        $query = 'INSERT INTO books (title, author, isbn) VALUES (:title, :author, :isbn)';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':title', $book->getTitle(), PDO::PARAM_STR);
        $stmt->bindValue(':author', $book->getAuthor(), PDO::PARAM_STR);
        $stmt->bindValue(':isbn', $book->getIsbn(), PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
     * Find all books in the database
     *
     * This method retrieves all books from the books table in the database
     * and returns them as an array of Book objects.
     * 
     * @return Book[] An array of Book objects.
     */
    public function findAll(): array
    {
        $query = 'SELECT * FROM books';
        $stmt = $this->pdo->query($query);
        $books = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $isbn = new ISBN($row['isbn']);
            $book = new Book($row['title'], $row['author'], $isbn);
            $book->setId((int) $row['id']);
            $book->setStatus((bool) $row['isAvailable']);
            $books[] = $book;
        }

        return $books;
    }

    /**
     * Find a book by ID.
     *
     * @param int $id
     * @return Book|null
     */
    public function findById(int $id): ?Book
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM books WHERE id = :id"
        );

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $book =  new Book(
                $result['title'],
                $result['author'],
                new ISBN($result['isbn']),
            );
            $book->setId((int) $result['id']);
            $book->setStatus((bool) $result['isAvailable']);
            return $book;
        }

        return null;
    }

    /**
     * Update the availability of a book.
     *
     * @param int $id The book ID.
     * @param bool $isAvailable The new availability status.
     * @return bool True on success, false on failure.
     */
    public function updateAvailability(int $id, bool $isAvailable): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE books SET isAvailable = :isAvailable WHERE id = :id"
        );

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':isAvailable', $isAvailable ? 1 : 0, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Delete a book by its ID
     *
     * This method deletes a book from the database based on the provided ID.
     * 
     * @param int $id The ID of the book to delete.
     * 
     * @return bool Returns true if the book was deleted, false otherwise.
     */
    public function deleteById(int $id): bool
    {
        $query = 'DELETE FROM books WHERE id = :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
