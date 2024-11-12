<?php

declare(strict_types=1);

use Mockery;
use Marcelofj\LibraryApp\Services\BookLoanService;
use Marcelofj\LibraryApp\Domain\Repositories\BookLoanRepository;
use Marcelofj\LibraryApp\Domain\Entities\BookLoan;
use DateTime;

/**
 * Set up the test environment before each test.
 *
 * @beforeEach
 * @return void
 */
beforeEach(function () {
    $this->bookLoanRepository = Mockery::mock(BookLoanRepository::class);
    $this->bookLoanService = new BookLoanService($this->bookLoanRepository);
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
 * Test adding a book loan.
 *
 * This test verifies that the `addBookLoan` method of the `BookLoanService` class
 * correctly calls the `save` method of the `BookLoanRepository`.
 *
 * @test
 * @return void
 */
test('should add a book loan', function () {
    $bookLoan = new BookLoan(1, 1, new DateTime('2023-01-01'), new DateTime('2023-01-10'));

    $this->bookLoanRepository->shouldReceive('save')->once()->with($bookLoan);

    $this->bookLoanService->addBookLoan($bookLoan);
});

/**
 * Test listing all book loans.
 *
 * This test verifies that the `listLoanBooks` method of the `BookLoanService` class
 * correctly calls the `listAll` method of the `BookLoanRepository` and returns the result.
 *
 * @test
 * @return void
 */
test('should list all loan books', function () {
    $bookLoans = [
        new BookLoan(1, 1, new DateTime('2023-01-01'), new DateTime('2023-01-10')),
        new BookLoan(2, 2, new DateTime('2023-02-01'), new DateTime('2023-02-10'))
    ];

    $this->bookLoanRepository->shouldReceive('listAll')->once()->andReturn($bookLoans);

    $result = $this->bookLoanService->listLoanBooks();

    expect($result)->toBe($bookLoans);
});

/**
 * Test fetching a book loan by ID.
 *
 * This test verifies that the `fetchLoanBookById` method of the `BookLoanService` class
 * correctly calls the `getById` method of the `BookLoanRepository` and returns the result.
 *
 * @test
 * @return void
 */
test('should fetch a loan book by id', function () {
    $bookLoan = new BookLoan(1, 1, new DateTime('2023-01-01'), new DateTime('2023-01-10'));

    $this->bookLoanRepository->shouldReceive('getById')->once()->with(1)->andReturn($bookLoan);

    $result = $this->bookLoanService->fetchLoanBookById(1);

    expect($result)->toBe($bookLoan);
});

/**
 * Test updating the status of a book loan.
 *
 * This test verifies that the `updateLoanBookStatus` method of the `BookLoanService` class
 * correctly calls the `updateStatus` method of the `BookLoanRepository` and returns the result.
 *
 * @test
 * @return void
 */
test('should update loan book status', function () {
    $this->bookLoanRepository->shouldReceive('updateStatus')->once()->with(1, 'returned')->andReturn(true);

    $result = $this->bookLoanService->updateLoanBookStatus(1, 'returned');

    expect($result)->toBeTrue();
});

/**
 * Test fetching all active loan books.
 *
 * This test verifies that the `fetchActiveLoanBooks` method of the `BookLoanService` class
 * correctly calls the `getActiveLoans` method of the `BookLoanRepository` and returns the result.
 *
 * @test
 * @return void
 */
test('should fetch all active loan books', function () {
    $activeLoans = [
        new BookLoan(1, 1, new DateTime('2023-01-01'), new DateTime('2023-01-10')),
        new BookLoan(2, 2, new DateTime('2023-02-01'), new DateTime('2023-02-10'))
    ];

    $this->bookLoanRepository->shouldReceive('getActiveLoans')->once()->andReturn($activeLoans);

    $result = $this->bookLoanService->fetchActiveLoanBooks();

    expect($result)->toBe($activeLoans);
});

/**
 * Test deleting a book loan by ID.
 *
 * This test verifies that the `deleteBookLoan` method of the `BookLoanService` class
 * correctly calls the `deleteById` method of the `BookLoanRepository` and returns the result.
 *
 * @test
 * @return void
 */
test('should delete a book loan by id', function () {
    $this->bookLoanRepository->shouldReceive('deleteById')->once()->with(1)->andReturn(true);

    $result = $this->bookLoanService->deleteBookLoan(1);

    expect($result)->toBeTrue();
});
