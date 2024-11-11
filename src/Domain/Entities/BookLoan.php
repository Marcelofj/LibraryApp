<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Domain\Entities;

use DateTime;
use Marcelofj\LibraryApp\Domain\Entities\Enums\BookLoanStatusEnum;

/**
 * Class representing a book loan in the library system.
 *
 * This class contains attributes and methods related to the process of
 * borrowing and returning a book, including the loan status and dates.
 */
class BookLoan
{
    /**
     * @var int|null $id The unique identifier for the book loan (optional, set later).
     */
    private ?int $id = null;

    /**
     * @var DateTime|null $returnDate The date when the book was returned (optional).
     */
    private ?DateTime $returnDate = null;

    /**
     * @var string $status The current status of the loan (default is 'active').
     */
    private string $status = 'active';

    /**
     * Constructor to initialize the book loan with book ID, user ID, loan date, and due date.
     *
     * @param int $book_id The ID of the borrowed book.
     * @param int $user_id The ID of the user borrowing the book.
     * @param DateTime $loanDate The date when the book was loaned.
     * @param DateTime $dueDate The date when the book is due to be returned.
     */
    public function __construct(
        private int $book_id,
        private int $user_id,
        private DateTime $loanDate,
        private DateTime $dueDate,
    ) {}

    /**
     * Set the unique identifier for the book loan.
     *
     * @param int $id The unique identifier for the book loan.
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the unique identifier for the book loan.
     *
     * @return int|null The unique ID of the book loan, or null if not set.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the current status of the book loan.
     *
     * Valid statuses are defined in the `BookLoanStatusEnum` class.
     *
     * @param string $status The status to set for the book loan.
     * @throws \InvalidArgumentException If the status is invalid.
     */
    public function setStatus(string $status): void
    {
        if (!BookLoanStatusEnum::isValid($status)) {
            throw new \InvalidArgumentException('Invalid status.');
        }

        $this->status = $status;
    }

    /**
     * Get the current status of the book loan.
     *
     * @return string The current status of the book loan (e.g., 'active', 'returned', 'overdue').
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Get the ID of the borrowed book.
     *
     * @return int The ID of the borrowed book.
     */
    public function getBookId(): int
    {
        return $this->book_id;
    }

    /**
     * Get the ID of the user who borrowed the book.
     *
     * @return int The ID of the user.
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * Get the date when the book was borrowed.
     *
     * @return DateTime The date the book was borrowed.
     */
    public function getLoanDate(): DateTime
    {
        return $this->loanDate;
    }

    /**
     * Get the due date for returning the book.
     *
     * @return DateTime The due date for returning the book.
     */
    public function getDueDate(): DateTime
    {
        return $this->dueDate;
    }

    /**
     * Get the return date of the book (if returned).
     *
     * @return DateTime|null The return date, or null if the book has not been returned.
     */
    public function getReturnDate(): ?DateTime
    {
        return $this->returnDate;
    }

    /**
     * Set the return date of the book.
     *
     * This method updates the return date once the book has been returned.
     *
     * @param \DateTime $date The date the book was returned.
     */
    public function setReturnDate(\DateTime $date): void
    {
        $this->returnDate = $date;
    }
}
