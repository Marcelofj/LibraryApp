<?php

declare(strict_types=1);

use Mockery;
use Marcelofj\LibraryApp\Domain\Entities\BookLoan;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\BookLoanRepositorySQLite;
use PDO;
use PDOStatement;
use DateTime;

/**
 * Set up the test environment before each test.
 * 
 * This function mocks the PDO instance and injects it into the BookLoanRepositorySQLite instance.
 * It uses reflection to set the private `$pdo` property of the repository.
 *
 * @beforeEach
 * @return void
 */
beforeEach(function () {
    $this->pdo = Mockery::mock(PDO::class);
    $this->repository = new BookLoanRepositorySQLite();

    $reflection = new ReflectionClass($this->repository);
    $property = $reflection->getProperty('pdo');
    $property->setAccessible(true);
    $property->setValue($this->repository, $this->pdo);
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
 * Test that a book loan can be saved.
 *
 * This test verifies that the `save` method of the `BookLoanRepositorySQLite` class 
 * correctly inserts a book loan into the database. It checks that the necessary SQL queries 
 * are prepared and executed.
 *
 * @test
 * @return void
 */
test('should save a book loan', function () {
    $loan = new BookLoan(1, 1, new DateTime('2023-01-01'), new DateTime('2023-01-10'));

    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('bindValue')->times(5);
    $stmt->shouldReceive('execute')->once();

    $this->pdo->shouldReceive('prepare')
        ->with("INSERT INTO book_loans (book_id, user_id, loan_date, due_date, status) 
             VALUES (:book_id, :user_id, :loan_date, :due_date, :status)")
        ->andReturn($stmt);

    $this->repository->save($loan);
});

/**
 * Test retrieving all book loans.
 *
 * This test verifies that the `listAll` method of the `BookLoanRepositorySQLite` class 
 * correctly retrieves all book loans from the database.
 *
 * @test
 * @return void
 */
test('should retrieve all book loans', function () {
    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('fetch')
        ->andReturn(
            ['id' => 1, 'book_id' => 1, 'user_id' => 1, 'loan_date' => '2023-01-01 00:00:00', 'due_date' => '2023-01-10 00:00:00', 'status' => 'active'],
            ['id' => 2, 'book_id' => 2, 'user_id' => 2, 'loan_date' => '2023-02-01 00:00:00', 'due_date' => '2023-02-10 00:00:00', 'status' => 'active'],
            false
        );

    $this->pdo->shouldReceive('query')
        ->with('SELECT * FROM book_loans')
        ->andReturn($stmt);

    $loans = $this->repository->listAll();

    expect($loans)->toHaveCount(2);
    expect($loans[0])->toBeInstanceOf(BookLoan::class);
    expect($loans[0]->getBookId())->toBe(1);
    expect($loans[1]->getBookId())->toBe(2);
});

/**
 * Test finding a book loan by its ID.
 *
 * This test verifies that the `getById` method correctly retrieves a book loan by its ID.
 * If the book loan is not found, it should return `null`.
 *
 * @test
 * @return void
 */
test('should find a book loan by id', function () {
    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('bindValue')->with(':id', 1, PDO::PARAM_INT);
    $stmt->shouldReceive('execute')->once();
    $stmt->shouldReceive('fetch')
        ->andReturn(
            ['id' => 1, 'book_id' => 1, 'user_id' => 1, 'loan_date' => '2023-01-01 00:00:00', 'due_date' => '2023-01-10 00:00:00', 'status' => 'active']
        );

    $this->pdo->shouldReceive('prepare')
        ->with('SELECT * FROM book_loans WHERE id = :id')
        ->andReturn($stmt);

    $loan = $this->repository->getById(1);

    expect($loan)->toBeInstanceOf(BookLoan::class);
    expect($loan->getBookId())->toBe(1);
    expect($loan->getStatus())->toBe('active');
});

/**
 * Test updating the status of a book loan.
 *
 * This test verifies that the `updateStatus` method correctly updates the status 
 * of a book loan in the database.
 *
 * @test
 * @return void
 */
test('should update book loan status', function () {
    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('bindValue')->with(':id', 1, PDO::PARAM_INT)->once();
    $stmt->shouldReceive('bindValue')->with(':status', 'returned', PDO::PARAM_STR)->once();
    $stmt->shouldReceive('execute')->once()->andReturnTrue();

    $this->pdo->shouldReceive('prepare')
        ->with('UPDATE book_loans SET status = :status WHERE id = :id')
        ->andReturn($stmt);

    $updated = $this->repository->updateStatus(1, 'returned');

    expect($updated)->toBeTrue();
});

/**
 * Test setting the return date of a book loan.
 *
 * This test verifies that the `setReturnDate` method correctly updates the return date 
 * of a book loan in the database.
 *
 * @test
 * @return void
 */
test('should set book loan return date', function () {
    $returnDate = new DateTime('2023-01-15 00:00:00');

    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('bindValue')->with(':id', 1, PDO::PARAM_INT)->once();
    $stmt->shouldReceive('bindValue')->with(':return_date', $returnDate->format('Y-m-d H:i:s'), PDO::PARAM_STR)->once();
    $stmt->shouldReceive('execute')->once()->andReturnTrue();

    $this->pdo->shouldReceive('prepare')
        ->with('UPDATE book_loans SET return_date = :return_date WHERE id = :id')
        ->andReturn($stmt);

    $updated = $this->repository->setReturnDate(1, $returnDate);

    expect($updated)->toBeTrue();
});

/**
 * Test retrieving all active book loans.
 *
 * This test verifies that the `getActiveLoans` method of the `BookLoanRepositorySQLite` class 
 * correctly retrieves all book loans with status 'active'.
 *
 * @test
 * @return void
 */
test('should retrieve all active book loans', function () {
    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('execute')->once();
    $stmt->shouldReceive('fetch')
        ->andReturn(
            ['id' => 1, 'book_id' => 1, 'user_id' => 1, 'loan_date' => '2023-01-01 00:00:00', 'due_date' => '2023-01-10 00:00:00', 'status' => 'active'],
            ['id' => 2, 'book_id' => 2, 'user_id' => 2, 'loan_date' => '2023-02-01 00:00:00', 'due_date' => '2023-02-10 00:00:00', 'status' => 'active'],
            false
        );

    $this->pdo->shouldReceive('prepare')
        ->with("SELECT * FROM book_loans WHERE status = 'active'")
        ->andReturn($stmt);

    $activeLoans = $this->repository->getActiveLoans();

    expect($activeLoans)->toHaveCount(2);
    expect($activeLoans[0])->toBeInstanceOf(BookLoan::class);
    expect($activeLoans[0]->getStatus())->toBe('active');
    expect($activeLoans[1]->getStatus())->toBe('active');
});

/**
 * Test deleting a book loan by its ID.
 *
 * This test verifies that the `deleteById` method of the `BookLoanRepositorySQLite` class 
 * correctly deletes a book loan from the database.
 *
 * @test
 * @return void
 */
test('should delete a book loan by id', function () {
    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('bindValue')->with(':id', 1, PDO::PARAM_INT)->once();
    $stmt->shouldReceive('execute')->once()->andReturnTrue();
    $stmt->shouldReceive('rowCount')->andReturn(1);

    $this->pdo->shouldReceive('prepare')
        ->with('DELETE FROM book_loans WHERE id = :id')
        ->andReturn($stmt);

    $deleted = $this->repository->deleteById(1);

    expect($deleted)->toBeTrue();
});
