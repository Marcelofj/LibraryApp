<?php

use Marcelofj\LibraryApp\Domain\Entities\ConcreteUser;
use Marcelofj\LibraryApp\Domain\ValueObjects\Email;

/**
 * Verifies that the user ID is set and retrieved correctly.
 * 
 * This test creates a `ConcreteUser` instance, sets the user ID to 123, 
 * and verifies that the ID can be retrieved correctly using the `getId` method.
 *
 * @test
 * @return void
 */
it('sets and gets the user id correctly', function () {
    $user = new ConcreteUser('John Doe', new Email('john.doe@example.com'));

    $user->setId(123);
    expect($user->getId())->toBe(123);
});

/**
 * Verifies that the user ID is set and retrieved correctly.
 * 
 * This test creates a `ConcreteUser` instance, sets the user ID to 123, 
 * and verifies that the ID can be retrieved correctly using the `getId` method.
 *
 * @test
 * @return void
 */
it('gets the user name correctly', function () {
    $user = new ConcreteUser('John Doe', new Email('john.doe@example.com'));

    expect($user->getName())->toBe('John Doe');
});

/**
 * Verifies that the user email is retrieved correctly.
 * 
 * This test creates a `ConcreteUser` instance with the email "john.doe@example.com"
 * and verifies that the `getEmail` method returns the correct email address.
 *
 * @test
 * @return void
 */
it('gets the user email correctly', function () {
    $email = new Email('john.doe@example.com');
    $user = new ConcreteUser('John Doe', $email);

    expect($user->getEmail())->toBe('john.doe@example.com');
});

/**
 * Verifies that the correct role is returned by the concrete subclass.
 * 
 * This test creates a `ConcreteUser` instance and checks if the `getRole` method
 * returns the hardcoded role value "role" as expected for this subclass.
 *
 * @test
 * @return void
 */
it('gets the role correctly from concrete subclass', function () {
    $user = new ConcreteUser('John Doe', new Email('john.doe@example.com'));

    expect($user->getRole())->toBe('role');
});
