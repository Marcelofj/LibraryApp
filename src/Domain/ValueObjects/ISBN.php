<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Domain\ValueObjects;

/**
 * ISBN Value Object
 *
 * This class represents the ISBN value object, which encapsulates the validation
 * and handling of an ISBN number. The ISBN number is validated during construction
 * to ensure it follows the correct format.
 */
final class ISBN
{
    /**
     * Constructor for ISBN Value Object.
     *
     * The constructor ensures that the ISBN provided is valid based on a regular expression.
     * If the ISBN is invalid, an exception will be thrown.
     * 
     * @param string $value The ISBN value to be assigned.
     * 
     * @throws \InvalidArgumentException if the ISBN is not valid.
     */
    function __construct(private string $value)
    {
        if (!$this->isValidISBN($value)) {
            throw new \InvalidArgumentException("Invalid ISBN!");
        }
    }

    /**
     * Validates the ISBN value.
     *
     * This private method checks if the provided ISBN follows the pattern of
     * a valid ISBN, which is expected to be in the format 'xxx-xxxxxxxxxx' 
     * (e.g., '978-1234567890').
     * 
     * @param string $isbn The ISBN to be validated.
     * 
     * @return bool Returns true if the ISBN is valid, false otherwise.
     */
    private function isValidISBN(string $isbn): bool
    {
        return preg_match('/\b\d{3}-\d{10}\b/', $isbn) === 1;
    }

    /**
     * Gets the ISBN value.
     *
     * Returns the internal value of the ISBN as a string.
     * 
     * @return string The ISBN value.
     */
    final public function getValue(): string
    {
        return $this->value;
    }
}
