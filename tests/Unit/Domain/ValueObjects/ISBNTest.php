<?php

use Marcelofj\LibraryApp\Domain\ValueObjects\ISBN;

/**
 * Tests that a valid ISBN can be created successfully.
 *
 * @return void
 */
test('creates a valid ISBN', function () {
    $isbn = new ISBN('123-1234567890');
    expect($isbn->getValue())->toBe('123-1234567890');
});

/**
 * Tests that an exception is thrown for an invalid ISBN format.
 *
 * @return void
 */
test('throws exception for invalid ISBN format', function () {
    try {
        new ISBN('1234567890');
    } catch (InvalidArgumentException $e) {
        expect($e)->toBeInstanceOf(InvalidArgumentException::class)
            ->and($e->getMessage())->toBe('Invalid ISBN!');
    }
});
