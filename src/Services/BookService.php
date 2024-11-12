<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Services;

use Marcelofj\LibraryApp\Domain\Repositories\BookRepository;
use Marcelofj\LibraryApp\Domain\Entities\Book;

/**
 * BookService class
 *
 * Service layer responsible for handling business logic related to books.
 * It interacts with the BookRepository to manage book data and performs operations such as adding, listing, and deleting books.
 */
class BookService
{
    /**
     * Constructor
     *
     * Initializes the BookService with the provided BookRepository.
     *
     * @param BookRepository $bookRepository The repository responsible for accessing and modifying book data.
     */
    public function __construct(private BookRepository $bookRepository) {}

    /**
     * Add a new book.
     *
     * This method adds a new book to the repository.
     *
     * @param Book $book The book entity to be added.
     * @return void
     */
    function addBook(Book $book): void
    {
        $this->bookRepository->save($book);
    }

    /**
     * List all books.
     *
     * This method retrieves all books from the repository.
     *
     * @return array An array of Book entities.
     */
    function listBooks(): array
    {
        return $this->bookRepository->findAll();
    }

    /**
     * Get a book by its ID.
     *
     * This method retrieves a specific book from the repository based on its ID.
     *
     * @param int $id The ID of the book to retrieve.
     * @return Book The book entity associated with the provided ID.
     */
    function getBookById(int $id): Book
    {
        return $this->bookRepository->findById($id);
    }

    /**
     * Update the availability status of a book.
     *
     * This method updates whether a book is available or not in the repository.
     *
     * @param int $id The ID of the book to update.
     * @param bool $isAvailable The new availability status of the book.
     * @return bool Returns true if the update was successful, false otherwise.
     */
    function updateBookAvailability(int $id, bool $isAvailable): bool
    {
        return $this->bookRepository->updateAvailability($id, $isAvailable);
    }

    /**
     * Delete a book by ID.
     *
     * This method deletes a book from the repository based on its ID.
     *
     * @param int $id The ID of the book to be deleted.
     * @return bool Returns true if the book was deleted, false otherwise.
     */
    function deleteBook(int $id): bool
    {
        return $this->bookRepository->deleteById($id);
    }
}
