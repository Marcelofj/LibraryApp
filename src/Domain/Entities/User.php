<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Domain\Entities;

use Marcelofj\LibraryApp\Domain\ValueObjects\Email;

/**
 * Abstract User class.
 *
 * This abstract class defines the common properties and methods for all user types in the library application,
 * including `id`, `name`, and `email`. It also includes an abstract `getRole` method that must be implemented 
 * by subclasses to specify the user role.
 */
abstract class User
{
    /**
     * Unique identifier for the user, initially null.
     *
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * Constructs a User instance with a name and email.
     *
     * @param string $name The user's name.
     * @param Email $email The user's email, represented as a value object.
     */
    public function __construct(protected string $name, protected Email $email) {}

    /**
     * Sets the user's unique identifier.
     *
     * @param int $id The user's ID.
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Retrieves the user's unique identifier.
     *
     * @return int|null The user's ID, or null if not set.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retrieves the user's name.
     *
     * @return string The name of the user.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Retrieves the user's email address.
     *
     * @return string The email address as a string.
     */
    public function getEmail(): string
    {
        return $this->email->getVAlue();
    }

    /**
     * Retrieves the user's role.
     *
     * This abstract method must be implemented by subclasses to define
     * the specific role of each user type.
     *
     * @return string The role of the user.
     */
    abstract function getRole(): string;
}
