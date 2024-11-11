<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Domain\ValueObjects;

/**
 * Email Value Object
 *
 * Represents a validated email address as a value object.
 * This class ensures that any email address provided is valid according to a specific format.
 */
final class Email
{
    /**
     * Constructor for the Email value object.
     *
     * @param string $value The email address to validate and store.
     * 
     * @throws \InvalidArgumentException If the provided email is invalid.
     */
    public function __construct(private string $value)
    {
        if (!$this->isValidEmail($value)) {
            throw new \InvalidArgumentException("Invalid e-mail!");
        }
    }

    /**
     * Validates the email format.
     *
     * Uses a regular expression to check if the email address follows the standard email format.
     * 
     * @param string $email The email address to validate.
     * 
     * @return bool Returns true if the email format is valid, false otherwise.
     */
    private function isValidEmail(string $email): bool
    {
        return preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email) === 1;
    }

    /**
     * Returns the email value.
     *
     * @return string The validated email address.
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
