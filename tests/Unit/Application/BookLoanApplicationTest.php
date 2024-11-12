<?php

declare(strict_types=1);

use Mockery;
use Marcelofj\LibraryApp\Application\BookLoanApplication;
use Marcelofj\LibraryApp\Application\UserApplication;
use Marcelofj\LibraryApp\Application\UserApplicationFactory;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\BookRepositorySQLite;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\TeacherRepositorySQLite;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\StudentRepositorySQLite;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\BookLoanRepositorySQLite;
use Marcelofj\LibraryApp\Domain\Entities\Book;
use Marcelofj\LibraryApp\Domain\Entities\UserInterface;
use Marcelofj\LibraryApp\Domain\Entities\BookLoan;
use Marcelofj\LibraryApp\Domain\ValueObjects\ISBN;
use Marcelofj\LibraryApp\Domain\Exceptions\BookNotFoundException;
use Marcelofj\LibraryApp\Domain\Exceptions\BookUnavailableException;
use Marcelofj\LibraryApp\Domain\Exceptions\BookNotReturnedException;
use DateTime;

/**
 * Set up the test environment before each test.
 * 
 * This function mocks the necessary dependencies for the BookLoanApplication tests.
 * It creates mock instances for the BookRepositorySQLite, TeacherRepositorySQLite, 
 * StudentRepositorySQLite, BookLoanRepositorySQLite, UserApplicationFactory, 
 * and UserApplication. It also initializes the BookLoanApplication instance 
 * with the mocked dependencies and configures the create method of 
 * UserApplicationFactory to return the mock of UserApplication.
 *
 * @beforeEach
 * @return void
 */
beforeEach(function () {
    $this->bookRepository = Mockery::mock(BookRepositorySQLite::class);
    $this->teacherRepository = Mockery::mock(TeacherRepositorySQLite::class);
    $this->studentRepository = Mockery::mock(StudentRepositorySQLite::class);
    $this->loanRepository = Mockery::mock(BookLoanRepositorySQLite::class);
    $this->userApplicationFactory = Mockery::mock(UserApplicationFactory::class);
    $this->userApplication = Mockery::mock(UserApplication::class);

    $this->bookLoanApplication = new BookLoanApplication(
        $this->bookRepository,
        $this->teacherRepository,
        $this->studentRepository,
        $this->loanRepository,
        $this->userApplicationFactory
    );

    $this->userApplicationFactory->shouldReceive('create')
        ->with($this->teacherRepository, $this->studentRepository)
        ->andReturn($this->userApplication);
});

/**
 * Clean up after each test.
 * 
 * This function closes all Mockery mock objects.
 *
 * @afterEach
 * @return void
 */
afterEach(function () {
    Mockery::close();
});

/**
 * Test: should checkout a book successfully
 *
 * This test checks if a book can be successfully checked out from the library.
 * It mocks the necessary dependencies and asserts that the result is true, 
 * indicating that the book checkout operation was successful.
 */
test('should checkout a book successfully', function () {
    $bookId = 1;
    $userId = 1;
    $loanDate = new DateTime();
    $dueDate = (new DateTime())->modify('+1 week');

    $book = new Book('Sample Book', 'Author Name', new ISBN('978-1234567890'));
    $book->setId($bookId);

    $user = Mockery::mock(UserInterface::class);

    $this->bookRepository->shouldReceive('findById')->with($bookId)->andReturn($book);
    $this->bookRepository->shouldReceive('updateAvailability')->with($bookId, false)->andReturn(true);
    $this->loanRepository->shouldReceive('save')->andReturn(true);
    $this->userApplication->shouldReceive('getUserById')->with($userId)->andReturn($user);

    $result = $this->bookLoanApplication->bookCheckout($bookId, $userId, $loanDate, $dueDate);

    expect($result)->toBeTrue();
});

/**
 * Test: should throw BookNotFoundException if book not found
 *
 * This test verifies that if the requested book does not exist in the repository, 
 * a BookNotFoundException is thrown.
 */
test('should throw BookNotFoundException if book not found', function () {
    $bookId = 1;
    $userId = 1;
    $loanDate = new DateTime();
    $dueDate = (new DateTime())->modify('+1 week');

    $this->bookRepository->shouldReceive('findById')->with($bookId)->andReturn(null);

    expect(function () use ($bookId, $userId, $loanDate, $dueDate) {
        $this->bookLoanApplication->bookCheckout($bookId, $userId, $loanDate, $dueDate);
    })->toThrow(BookNotFoundException::class);
});

/**
 * Test: should throw BookNotReturnedException if book is not available
 *
 * This test ensures that if the book is not available for checkout (e.g., already loaned out), 
 * a BookNotReturnedException is thrown.
 */
test('should throw BookNotReturnedException if book is not available', function () {
    $bookId = 1;
    $userId = 1;
    $loanDate = new DateTime();
    $dueDate = (new DateTime())->modify('+1 week');

    $book = Mockery::mock(Book::class);
    $book->shouldReceive('getStatus')->andReturn(false);

    $this->bookRepository->shouldReceive('findById')->with($bookId)->andReturn($book);

    expect(function () use ($bookId, $userId, $loanDate, $dueDate) {
        $this->bookLoanApplication->bookCheckout($bookId, $userId, $loanDate, $dueDate);
    })->toThrow(BookNotReturnedException::class);
});

/**
 * Test: should checkin a book successfully
 *
 * This test checks if a book can be successfully checked in after being returned. 
 * It mocks the necessary dependencies and asserts that the result is true, 
 * indicating the book check-in operation was successful.
 */
test('should checkin a book successfully', function () {
    $bookId = 1;
    $loanId = 1;
    $returnDate = new DateTime();

    $book = Mockery::mock(Book::class);
    $book->shouldReceive('getStatus')->andReturn(false);

    $bookLoan = Mockery::mock(BookLoan::class);
    $bookLoan->shouldReceive('getDueDate')->andReturn((new DateTime())->modify('+1 week'));

    $this->bookRepository->shouldReceive('findById')->with($bookId)->andReturn($book);
    $this->bookRepository->shouldReceive('updateAvailability')->with($bookId, true)->andReturn(true);
    $this->loanRepository->shouldReceive('setReturnDate')->with($loanId, $returnDate)->andReturn(true);
    $this->loanRepository->shouldReceive('getById')->with($bookId)->andReturn($bookLoan);
    $this->loanRepository->shouldReceive('updateStatus')->with($loanId, 'returned')->andReturn(true);

    $result = $this->bookLoanApplication->bookCheckin($bookId, $loanId, $returnDate);
    expect($result)->toBeTrue();
});

/**
 * Test: should throw BookNotFoundException if book not found during checkin
 *
 * This test verifies that if the requested book is not found during check-in, 
 * a BookNotFoundException is thrown.
 */
test('should throw BookNotFoundException if book not found during checkin', function () {
    $bookId = 1;
    $loanId = 1;
    $returnDate = new DateTime();

    $this->bookRepository->shouldReceive('findById')->with($bookId)->andReturn(null);

    expect(function () use ($bookId, $loanId, $returnDate) {
        $this->bookLoanApplication->bookCheckin($bookId, $loanId, $returnDate);
    })->toThrow(BookNotFoundException::class);
});

/**
 * Test: should throw BookUnavailableException if book is already available during checkin
 *
 * This test checks if a BookUnavailableException is thrown when attempting to check in a book 
 * that is already marked as available.
 */
test('should throw BookUnavailableException if book is already available during checkin', function () {
    $bookId = 1;
    $loanId = 1;
    $returnDate = new DateTime();

    $book = Mockery::mock(Book::class);
    $book->shouldReceive('getStatus')->andReturn(true);

    $this->bookRepository->shouldReceive('findById')->with($bookId)->andReturn($book);

    expect(function () use ($bookId, $loanId, $returnDate) {
        $this->bookLoanApplication->bookCheckin($bookId, $loanId, $returnDate);
    })->toThrow(BookUnavailableException::class);
});
