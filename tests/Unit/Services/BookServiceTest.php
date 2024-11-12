<?php

use Marcelofj\LibraryApp\Services\BookService;
use Marcelofj\LibraryApp\Domain\Repositories\BookRepository;
use Marcelofj\LibraryApp\Domain\Entities\Book;
use Marcelofj\LibraryApp\Domain\ValueObjects\ISBN;
use Mockery;

/**
 * Set up the test environment before each test.
 *
 * This function mocks the `BookRepository` and initializes the `BookService`
 * with the mocked repository for testing purposes.
 *
 * @beforeEach
 * @return void
 */
beforeEach(function () {
    $this->bookRepositoryMock = Mockery::mock(BookRepository::class);
    $this->bookService = new BookService($this->bookRepositoryMock);
});

/**
 * Clean up after each test.
 * 
 * This function closes all Mockery mock objects after each test execution.
 *
 * @afterEach
 * @return void
 */
afterEach(function () {
    Mockery::close();
});

/**
 * Test adding a new book.
 *
 * This test verifies that the `addBook` method in the `BookService` class 
 * correctly calls the `save` method of the `BookRepository` to persist 
 * the new book in the database. It ensures the book is passed correctly 
 * to the repository and the `save` method is called once.
 *
 * @test
 * @return void
 */
test('should add a new book', function () {
    $book = new Book('The Great Gatsby', 'F. Scott Fitzgerald', new ISBN('978-0743273565'));

    $this->bookRepositoryMock->shouldReceive('save')
        ->once()
        ->with($book);

    $this->bookService->addBook($book);
});

/**
 * Test listing all books.
 *
 * This test checks that the `listBooks` method in the `BookService` class 
 * correctly fetches all books by calling the `findAll` method of the `BookRepository` 
 * and returns the list of books.
 *
 * @test
 * @return void
 */
test('should list all books', function () {
    $books = [
        new Book('The Great Gatsby', 'F. Scott Fitzgerald', new ISBN('978-0743273565')),
        new Book('1984', 'George Orwell', new ISBN('978-0451524935'))
    ];

    $this->bookRepositoryMock->shouldReceive('findAll')
        ->once()
        ->andReturn($books);
    $result = $this->bookService->listBooks();

    expect($result)->toEqual($books);
});

/**
 * Test deleting a book by ID.
 *
 * This test ensures that the `deleteBook` method in the `BookService` class 
 * correctly calls the `deleteById` method of the `BookRepository` to delete 
 * a book by its ID. It verifies that the book is deleted successfully 
 * by checking the return value.
 *
 * @test
 * @return void
 */
test('should delete a book by id', function () {
    $bookId = 1;

    $this->bookRepositoryMock->shouldReceive('deleteById')
        ->once()
        ->with($bookId)
        ->andReturn(true);

    $result = $this->bookService->deleteBook($bookId);

    expect($result)->toBeTrue();
});

/**
 * Test updating book availability.
 *
 * This test verifies that the `updateBookAvailability` method of the `BookLoanService` class
 * correctly calls the `updateAvailability` method of the `BookRepository` and returns the result.
 *
 * @test
 * @return void
 */
test('should update book availability', function () {
    $this->bookRepositoryMock->shouldReceive('updateAvailability')
        ->once()
        ->with(1, true)
        ->andReturn(true);

    $result = $this->bookService->updateBookAvailability(1, true);
    expect($result)->toBeTrue();
});
