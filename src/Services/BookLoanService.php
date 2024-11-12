<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Services;

use Marcelofj\LibraryApp\Domain\Repositories\BookLoanRepository;
use Marcelofj\LibraryApp\Domain\Entities\BookLoan;

/**
 * Class BookLoanService
 *
 * This service class provides business logic for managing book loans.
 * It interacts with the BookLoanRepository to handle operations such as adding,
 * listing, updating, and deleting book loans.
 */
class BookLoanService
{
    /**
     * Constructor
     *
     * Initializes the BookLoanService with a BookLoanRepository instance.
     * This repository will be used to interact with the data layer for managing book loans.
     *
     * @param BookLoanRepository $bookLoanRepository The repository used for book loan data operations.
     */
    public function __construct(private BookLoanRepository $bookLoanRepository) {}

    /**
     * Add a new book loan.
     *
     * @param BookLoan $bookLoan The book loan entity to be added.
     */
    function addBookLoan(BookLoan $bookLoan): void
    {
        $this->bookLoanRepository->save($bookLoan);
    }

    /**
     * List all book loans.
     *
     * @return BookLoan[] An array of BookLoan objects.
     */
    function listLoanBooks(): array
    {
        return $this->bookLoanRepository->listAll();
    }

    /**
     * Fetch a book loan by its ID.
     *
     * @param int $id The ID of the book loan.
     * @return BookLoan|null The book loan entity, or null if not found.
     */
    function fetchLoanBookById(int $id): ?BookLoan
    {
        return $this->bookLoanRepository->getById($id);
    }

    /**
     * Update the status of a book loan.
     *
     * @param int $id The ID of the book loan.
     * @param string $status The new status of the book loan.
     * @return bool True if the status was updated, false otherwise.
     */
    function updateLoanBookStatus(int $id, string $status): bool
    {
        return $this->bookLoanRepository->updateStatus($id, $status);
    }

    /**
     * Fetch all active book loans.
     *
     * @return BookLoan[] An array of active BookLoan objects.
     */
    function fetchActiveLoanBooks(): array
    {
        return $this->bookLoanRepository->getActiveLoans();
    }

    /**
     * Delete a book loan by its ID.
     *
     * @param int $id The ID of the book loan to delete.
     * @return bool True if the loan was deleted, false otherwise.
     */
    function deleteBookLoan(int $id): bool
    {
        return $this->bookLoanRepository->deleteById($id);
    }
}
