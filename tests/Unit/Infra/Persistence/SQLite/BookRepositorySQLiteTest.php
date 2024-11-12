<?php

declare(strict_types=1);

use Mockery;
use Marcelofj\LibraryApp\Domain\ValueObjects\ISBN;
use Marcelofj\LibraryApp\Domain\Entities\Book;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\BookRepositorySQLite;
use PDO;
use PDOStatement;

/**
 * Set up before each test.
 *
 * @return void
 */
beforeEach(function () {
    $this->pdo = Mockery::mock(PDO::class);
    $this->repository = new BookRepositorySQLite();
    $reflection = new ReflectionClass($this->repository);
    $property = $reflection->getProperty('pdo');
    $property->setAccessible(true);
    $property->setValue($this->repository, $this->pdo);
});

/**
 * Clean up after each test.
 *
 * @return void
 */
afterEach(function () {
    Mockery::close();
});

/**
 * Test the saving of a book to the database.
 *
 * @test
 * @return void
 */
test('should save a book', function () {
    $book = new Book('Sample Book', 'Author Name', new ISBN('978-1234567897'));

    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('bindValue')->times(3);
    $stmt->shouldReceive('execute')->once();

    $this->pdo->shouldReceive('prepare')
        ->with('INSERT INTO books (title, author, isbn) VALUES (:title, :author, :isbn)')
        ->andReturn($stmt);

    $this->repository->save($book);
});

/**
 * Test retrieving all books from the database.
 *
 * @test
 * @return void
 */
test('should retrieve all books', function () {
    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('fetch')
        ->andReturn(
            ['id' => 1, 'title' => 'Book 1', 'author' => 'Author 1', 'isbn' => '978-1234567897', 'isAvailable' => true],
            ['id' => 2, 'title' => 'Book 2', 'author' => 'Author 2', 'isbn' => '978-1234567898', 'isAvailable' => false],
            false
        );

    $this->pdo->shouldReceive('query')
        ->with('SELECT * FROM books')
        ->andReturn($stmt);

    $books = $this->repository->findAll();

    expect($books)->toHaveCount(2);
    expect($books[0])->toBeInstanceOf(Book::class);
    expect($books[0]->getTitle())->toBe('Book 1');
    expect($books[1]->getTitle())->toBe('Book 2');
});

/**
 * Test deleting a book by its ID.
 *
 * @test
 * @return void
 */
test('should delete a book by id', function () {
    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('bindValue')->with(':id', 1, PDO::PARAM_INT)->once();
    $stmt->shouldReceive('execute')->once()->andReturnTrue();
    $stmt->shouldReceive('rowCount')->andReturn(1);

    $this->pdo->shouldReceive('prepare')
        ->with('DELETE FROM books WHERE id = :id')
        ->andReturn($stmt);

    $deleted = $this->repository->deleteById(1);

    expect($deleted)->toBeTrue();
});

/**
 * Test finding a book by its ID.
 *
 * @test
 * @return void
 */
test('should find a book by id', function () {
    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('bindValue')->with(':id', 1, PDO::PARAM_INT)->once();
    $stmt->shouldReceive('execute')->once();
    $stmt->shouldReceive('fetch')->andReturn([
        'id' => 1,
        'title' => 'Sample Book',
        'author' => 'Author Name',
        'isbn' => '978-1234567897',
        'isAvailable' => true,
    ]);

    $this->pdo->shouldReceive('prepare')
        ->with('SELECT * FROM books WHERE id = :id')
        ->andReturn($stmt);

    $book = $this->repository->findById(1);

    expect($book)->toBeInstanceOf(Book::class);
    expect($book->getId())->toBe(1);
    expect($book->getTitle())->toBe('Sample Book');
});

/**
 * Test updating the availability status of a book.
 *
 * @test
 * @return void
 */
test('should update book availability', function () {
    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('bindValue')->with(':id', 1, PDO::PARAM_INT)->once();
    $stmt->shouldReceive('bindValue')->with(':isAvailable', 0, PDO::PARAM_INT)->once();
    $stmt->shouldReceive('execute')->once()->andReturnTrue();

    $this->pdo->shouldReceive('prepare')
        ->with('UPDATE books SET isAvailable = :isAvailable WHERE id = :id')
        ->andReturn($stmt);

    $updated = $this->repository->updateAvailability(1, false);

    expect($updated)->toBeTrue();
});
