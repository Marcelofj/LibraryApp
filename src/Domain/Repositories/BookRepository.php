<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Domain\Repositories;

use Marcelofj\LibraryApp\Domain\Entities\Book;

/**
 * Interface for Book Repository
 *
 * This interface defines the operations that can be performed on books in
 * a data store, including saving, retrieving, and deleting books.
 */
interface BookRepository
{
    /**
     * Save a book to the repository.
     *
     * Persists the given book to the underlying data store (e.g., database).
     * 
     * @param Book $book The book entity to be saved.
     */
    public function save(Book $book): void;

    /**
     * Retrieve all books from the repository.
     *
     * Fetches a list of all books stored in the repository.
     * 
     * @return Book[] An array of Book entities.
     */
    public function findAll(): array;

    /**
     * Retrieve a book by its ID.
     *
     * Fetches a specific book from the repository using its unique identifier.
     *
     * @param int $id The ID of the book to be retrieved.
     * @return Book|null Returns the book if found, or null if not found.
     */
    public function findById(int $id): ?Book;

    /**
     * Update the availability status of a book.
     *
     * Updates the availability status of the book in the repository (e.g., available or unavailable).
     *
     * @param int $id The ID of the book.
     * @param bool $isAvailable The new availability status.
     * @return bool Returns true if the availability status was successfully updated, false otherwise.
     */
    public function updateAvailability(int $id, bool $isAvailable): bool;

    /**
     * Delete a book by its ID.
     *
     * Deletes a book from the repository based on the provided ID.
     * 
     * @param int $id The unique identifier of the book to be deleted.
     * @return bool Returns true if the book was successfully deleted, false otherwise.
     */
    public function deleteById(int $id): bool;
}
