<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Application;

use Marcelofj\LibraryApp\Infra\Persistence\SQLite\BookRepositorySQLite;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\TeacherRepositorySQLite;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\StudentRepositorySQLite;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\BookLoanRepositorySQLite;
use Marcelofj\LibraryApp\Domain\Entities\BookLoan;
use Marcelofj\LibraryApp\Domain\Exceptions\BookNotFoundException;
use Marcelofj\LibraryApp\Domain\Exceptions\BookUnavailableException;
use Marcelofj\LibraryApp\Domain\Exceptions\BookNotReturnedException;
use Marcelofj\LibraryApp\Application\UserApplicationFactory;

/**
 * Class BookLoanApplication
 *
 * This class handles the business logic for managing book loans, including the processes
 * for checking out and checking in books. It interacts with various repositories 
 * to manage the persistence of data related to books, users, and loans.
 */
class BookLoanApplication
{
    /**
     * Constructor for the BookLoanApplication class.
     *
     * Initializes the dependencies required to manage book loans.
     *
     * @param BookRepositorySQLite $bookRepository The repository responsible for book persistence.
     * @param TeacherRepositorySQLite $teacherRepository The repository responsible for teacher persistence.
     * @param StudentRepositorySQLite $studentRepository The repository responsible for student persistence.
     * @param BookLoanRepositorySQLite $loanRepository The repository responsible for book loan persistence.
     * @param UserApplicationFactory $userApplicationFactory The factory responsible for creating user applications.
     */
    public function __construct(
        private BookRepositorySQLite $bookRepository,
        private TeacherRepositorySQLite $teacherRepository,
        private StudentRepositorySQLite $studentRepository,
        private BookLoanRepositorySQLite $loanRepository,
        private UserApplicationFactory $userApplicationFactory,
    ) {}

    /**
     * Checks out a book for a user.
     *
     * This method performs the necessary checks to ensure that the book is available
     * for checkout and that the user is valid. It creates a new book loan and saves it
     * in the loan repository.
     *
     * @param int $bookId The ID of the book to be checked out.
     * @param int $userId The ID of the user checking out the book.
     * @param \DateTime $loanDate The date when the book is checked out.
     * @param \DateTime $dueDate The date when the book is due for return.
     *
     * @return bool Returns true if the book was successfully checked out, otherwise false.
     *
     * @throws BookNotFoundException If the book with the specified ID was not found.
     * @throws BookNotReturnedException If the book has already been checked out and not returned.
     */
    public function bookCheckout(int $bookId, int $userId, \DateTime $loanDate, \DateTime $dueDate): bool
    {
        $book = $this->bookRepository->findById($bookId);

        if (!$book) {
            throw new BookNotFoundException();
        }

        if ($book->getStatus() === false) {
            throw new BookNotReturnedException();
        }

        $userApplication = $this->userApplicationFactory->create($this->teacherRepository, $this->studentRepository);

        $user = $userApplication->getUserById($userId);
        if (!$user) {
            return false;
        }

        $bookLoan = new BookLoan($bookId, $userId, $loanDate, $dueDate);

        $this->bookRepository->updateAvailability($bookId, false);

        return $this->loanRepository->save($bookLoan);
    }

    /**
     * Checks in a book after it is returned.
     *
     * This method updates the availability of the book, sets the return date, 
     * and updates the loan status to either 'overdue' or 'returned' depending on
     * whether the book was returned after the due date.
     *
     * @param int $bookId The ID of the book being returned.
     * @param int $loanId The ID of the loan associated with the book being returned.
     * @param \DateTime $returnDate The date when the book is returned.
     *
     * @return bool Returns true if the return status was successfully updated, otherwise false.
     *
     * @throws BookNotFoundException If the book with the specified ID was not found.
     * @throws BookUnavailableException If the book was already returned or unavailable.
     */
    public function bookCheckin(int $bookId, int $loanId, \DateTime $returnDate): bool
    {
        $book = $this->bookRepository->findById($bookId);

        if (!$book) {
            throw new BookNotFoundException();
        };

        if ($book->getStatus() === true) {
            throw new BookUnavailableException();
        }

        $this->bookRepository->updateAvailability($bookId, true);

        $this->loanRepository->setReturnDate($loanId, $returnDate);

        $dueDate = $this->loanRepository->getById($bookId)->getDueDate();

        if ($returnDate > $dueDate) {
            return $this->loanRepository->updateStatus($loanId, 'overdue');
        }

        return $this->loanRepository->updateStatus($loanId, 'returned');
    }
}
