<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Infra\Persistence\SQLite;

use Marcelofj\LibraryApp\Domain\Entities\BookLoan;
use Marcelofj\LibraryApp\Domain\Repositories\BookLoanRepository;
use PDO;

/**
 * Class BookLoanRepositorySQLite
 *
 * Concrete implementation of the `BookLoanRepository` interface for SQLite database persistence.
 * Handles CRUD operations for book loans using PDO for database interaction.
 */
class BookLoanRepositorySQLite implements BookLoanRepository
{
    private PDO $pdo;

    /**
     * Constructor to initialize the repository with a PDO connection.
     *
     * @param PDO $pdo The PDO connection to the SQLite database.
     */
    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Save a new book loan or update an existing loan.
     *
     * @param BookLoan $bookLoan The book loan entity.
     * @return bool True on success, false on failure.
     */
    public function save(BookLoan $bookLoan): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO book_loans (book_id, user_id, loan_date, due_date, status) 
             VALUES (:book_id, :user_id, :loan_date, :due_date, :status)"
        );

        $stmt->bindValue(':book_id', $bookLoan->getBookId(), PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $bookLoan->getUserId(), PDO::PARAM_INT);
        $stmt->bindValue(':loan_date', $bookLoan->getLoanDate()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':due_date', $bookLoan->getDueDate()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':status', $bookLoan->getStatus(), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * List all book loans.
     *
     * @return BookLoan[] Array of BookLoan entities.
     */
    public function listAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM book_loans");
        $loans = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $loan = new BookLoan(
                $row['book_id'],
                $row['user_id'],
                new \DateTime($row['loan_date']),
                new \DateTime($row['due_date']),
            );
            $loan->setStatus($row['status']);
            $loan->setId((int) $row['id']);
            if (!empty($row['return_date'])) {
                $loan->setReturnDate(new \DateTime($row['return_date']));
            }
            $loans[] = $loan;
        }

        return $loans;
    }

    /**
     * Get a book loan by its ID.
     *
     * @param int $id The ID of the book loan.
     * @return BookLoan|null The BookLoan entity or null if not found.
     */
    public function getById(int $id): ?BookLoan
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM book_loans WHERE id = :id"
        );

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $loan =  new BookLoan(
                $result['book_id'],
                $result['user_id'],
                new \DateTime($result['loan_date']),
                new \DateTime($result['due_date']),
            );
            $loan->setStatus($result['status']);
            $loan->setId((int) $result['id']);
            if (!empty($result['return_date'])) {
                $loan->setReturnDate(new \DateTime($result['return_date']));
            }
            return $loan;
        }

        return null;
    }

    /**
     * Update the status of a book loan.
     *
     * @param int $id The ID of the book loan.
     * @param string $status The new status of the loan (e.g., 'returned', 'active', 'overdue').
     * @return bool True on success, false on failure.
     */
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE book_loans SET status = :status WHERE id = :id"
        );

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Set the return date for a book loan.
     *
     * @param int $id The loan ID.
     * @param \DateTime $return_date The return date.
     * @return bool True on success, false on failure.
     */
    public function setReturnDate(int $id, \DateTime $return_date): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE book_loans SET return_date = :return_date WHERE id = :id"
        );

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':return_date', $return_date->format('Y-m-d H:i:s'), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Get all active book loans (loans not yet returned).
     *
     * @return BookLoan[] Array of active BookLoan entities.
     */
    public function getActiveLoans(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM book_loans WHERE status = 'active'"
        );
        $stmt->execute();

        $loans = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $loan = new BookLoan(
                $row['book_id'],
                $row['user_id'],
                new \DateTime($row['loan_date']),
                new \DateTime($row['due_date'])
            );
            $loan->getStatus($row['status']);
            $loan->setId((int) $row['id']);
            $loans[] = $loan;
        }

        return $loans;
    }

    /**
     * Delete a book loan by its ID
     *
     * This method deletes a book loan from the database based on the provided ID.
     * 
     * @param int $id The ID of the book loan to delete.
     * 
     * @return bool Returns true if the book loan was deleted, false otherwise.
     */
    public function deleteById(int $id): bool
    {
        $query = 'DELETE FROM book_loans WHERE id = :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
