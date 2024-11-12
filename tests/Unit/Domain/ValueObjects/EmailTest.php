<?php

use Marcelofj\LibraryApp\Domain\ValueObjects\Email;

/**
 * Tests if a valid email address is created successfully.
 *
 * @return void
 */
test('creates a valid email successfully', function () {
    $email = new Email('email@email.com.br');
    expect($email->getValue())->toBe('email@email.com.br');
});

/**
 * Tests if an exception is thrown for an email with an invalid format.
 *
 * @return void
 */
test('throws exception for invalid email format', function () {
    try {
        new Email('email@email');
    } catch (InvalidArgumentException $e) {
        expect($e)->toBeInstanceOf(InvalidArgumentException::class)
            ->and($e->getMessage())->toBe('Invalid e-mail!');
    }
});

/**
 * Tests if an exception is thrown for an email missing the "@" symbol.
 *
 * @return void
 */
test('throws exception for missing "@" symbol', function () {
    try {
        new Email('emailemail.com.br');
    } catch (InvalidArgumentException $e) {
        expect($e)->toBeInstanceOf(InvalidArgumentException::class)
            ->and($e->getMessage())->toBe('Invalid e-mail!');
    }
});

/**
 * Tests if an exception is thrown for an email with an invalid domain part.
 *
 * @return void
 */
test('throws exception for invalid domain', function () {
    try {
        new Email('email@email');
    } catch (InvalidArgumentException $e) {
        expect($e)->toBeInstanceOf(InvalidArgumentException::class)
            ->and($e->getMessage())->toBe('Invalid e-mail!');
    }
});

/**
 * Tests if an exception is thrown for an email with an invalid top-level domain.
 *
 * @return void
 */
test('throws exception for invalid top-level domain', function () {
    try {
        new Email('email@email.c');
    } catch (InvalidArgumentException $e) {
        expect($e)->toBeInstanceOf(InvalidArgumentException::class)
            ->and($e->getMessage())->toBe('Invalid e-mail!');
    }
});
