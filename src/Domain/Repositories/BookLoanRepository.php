<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Domain\Repositories;

use Marcelofj\LibraryApp\Domain\Entities\BookLoan;

/**
 * Interface for the BookLoan repository.
 *
 * This interface defines the contract for the persistence layer that handles
 * operations related to book loans. The implementing class will manage the
 * persistence of book loan records, including saving, retrieving, updating,
 * and deleting loan records.
 */
interface BookLoanRepository
{
    /**
     * Saves a book loan to the repository.
     *
     * This method persists the given book loan entity.
     *
     * @param BookLoan $bookLoan The book loan entity to be saved.
     * @return bool Returns true if the book loan was successfully saved, false otherwise.
     */
    public function save(BookLoan $bookLoan): bool;

    /**
     * Lists all book loans in the repository.
     *
     * This method retrieves all book loans stored in the repository.
     *
     * @return array An array of all book loan entities.
     */
    public function listAll(): array;

    /**
     * Retrieves a book loan by its ID.
     *
     * This method fetches a specific book loan using its unique identifier.
     *
     * @param int $id The ID of the book loan.
     * @return BookLoan|null Returns the book loan if found, or null if not found.
     */
    public function getById(int $id): ?BookLoan;

    /**
     * Updates the status of a book loan.
     *
     * This method allows updating the status of a specific book loan (e.g., from 'active' to 'returned').
     *
     * @param int $id The ID of the book loan to be updated.
     * @param string $status The new status of the book loan.
     * @return bool Returns true if the status was successfully updated, false otherwise.
     */
    public function updateStatus(int $id, string $status): bool;

    /**
     * Retrieves all active book loans.
     *
     * This method fetches all book loans that are currently active (not returned).
     *
     * @return array An array of active book loan entities.
     */
    public function getActiveLoans(): array;

    /**
     * Deletes a book loan by its ID.
     *
     * This method deletes a specific book loan from the repository by its unique identifier.
     *
     * @param int $id The ID of the book loan to be deleted.
     * @return bool Returns true if the book loan was successfully deleted, false otherwise.
     */
    public function deleteById(int $id): bool;
}
