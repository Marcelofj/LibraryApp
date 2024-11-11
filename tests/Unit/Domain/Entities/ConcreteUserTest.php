<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Domain\Entities;

use Marcelofj\LibraryApp\Domain\ValueObjects\Email;

/**
 * Concrete implementation of the User class.
 *
 * This class extends the abstract `User` class and provides a concrete implementation
 * of the `getRole` method. It is intended to be used for creating specific user types
 * by passing a name and email address to the constructor.
 *
 * @package Marcelofj\LibraryApp\Domain\Entities
 */
class ConcreteUser extends User
{
    /**
     * ConcreteUser constructor.
     * 
     * This constructor calls the parent constructor of the `User` class,
     * passing the name and email address to initialize the user.
     *
     * @param string $name The name of the user.
     * @param Email $email The email address of the user (Email value object).
     * 
     * @return void
     */
    public function __construct(string $name, Email $email)
    {
        parent::__construct($name, $email);
    }

    /**
     * Get the role of the user.
     * 
     * This method provides a concrete implementation of the `getRole` method,
     * returning the role of the user as a string.
     *
     * @return string The role of the user.
     */
    public function getRole(): string
    {
        return 'role';
    }
}
