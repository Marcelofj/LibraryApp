<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Domain\Entities;

use Marcelofj\LibraryApp\Domain\ValueObjects\ISBN;

/**
 * Class representing a book in the library system.
 *
 * This class contains all the necessary attributes and methods for managing
 * the properties of a book, such as title, author, ISBN, availability status,
 * and checkout/check-in actions.
 */
class Book
{
    /**
     * @var int|null $id The unique identifier for the book (optional, set later).
     */
    private ?int $id = null;

    /**
     * @var bool $isAvailable The availability status of the book.
     * Default is true, meaning the book is available when created.
     */
    private bool $isAvailable = true;

    /**
     * Constructor to initialize the book with title, author, and ISBN.
     *
     * @param string $title The title of the book.
     * @param string $author The author of the book.
     * @param ISBN $isbn The ISBN value object of the book.
     */
    public function __construct(private string $title, private string $author, private ISBN $isbn) {}

    /**
     * Set the unique identifier for the book.
     *
     * @param int $id The unique identifier of the book.
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the unique identifier for the book.
     *
     * @return int|null The book's ID, or null if it hasn't been set yet.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the title of the book.
     *
     * @return string The title of the book.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the author of the book.
     *
     * @return string The author of the book.
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Get the ISBN of the book.
     *
     * @return string The ISBN of the book.
     */
    public function getIsbn(): string
    {
        return $this->isbn->getValue();
    }

    /**
     * Get the availability status of the book.
     *
     * This method checks if the book is available for checkout.
     *
     * @return bool The availability status of the book (true if available, false if not).
     */
    public function getStatus(): bool
    {
        return $this->isAvailable;
    }

    /**
     * Set the availability status of the book.
     *
     * This method updates the availability status of the book (available or not).
     *
     * @param bool $status The new availability status of the book.
     */
    public function setStatus(bool $status): void
    {
        $this->isAvailable = $status;
    }
}
