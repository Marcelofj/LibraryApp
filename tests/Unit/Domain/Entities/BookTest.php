<?php

use Marcelofj\LibraryApp\Domain\Entities\Book;
use Marcelofj\LibraryApp\Domain\ValueObjects\ISBN;

/**
 * Sets up necessary instances before each test.
 */
beforeEach(function () {
    $this->isbn = new ISBN('978-3161484100');
    $this->book = new Book('Clean Code', 'Robert C. Martin', $this->isbn);
});

/**
 * Tests that the book initializes with the correct properties.
 *
 * @return void
 */
test('it initializes with correct properties', function () {
    expect($this->book->getTitle())->toBe('Clean Code')
        ->and($this->book->getAuthor())->toBe('Robert C. Martin')
        ->and($this->book->getIsbn())->toBe('978-3161484100')
        ->and($this->book->getStatus())->toBeTrue();
});

/**
 * Tests that an exception is thrown for an invalid ISBN format.
 *
 * @return void
 */
test('it throws exception for invalid ISBN format', function () {
    try {
        new ISBN('1231234567890');
    } catch (InvalidArgumentException $e) {
        expect($e->getMessage())->toBe('Invalid ISBN!');
    }
});

/**
 * Tests that the book ID can be set and retrieved.
 *
 * @return void
 */
test('it can set and retrieve the book id', function () {
    $this->book->setId(1);
    expect($this->book->getId())->toBe(1);
});

/**
 * Tests change book status, setting its availability to false.
 *
 * @return void
 */
test('it set the book unavailable', function () {
    $this->book->setStatus(false);
    expect($this->book->getStatus())->toBeFalse();
});
