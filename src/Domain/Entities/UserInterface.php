<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Domain\Entities;

/**
 * Interface defining the structure for a user in the library system.
 *
 * This interface specifies the methods that any user entity (e.g., teacher, student)
 * must implement to interact with the library system, including methods for retrieving
 * user information such as ID, name, email, and role.
 */
interface UserInterface
{
    /**
     * Set the unique identifier for the user.
     *
     * @param int $id The unique identifier for the user.
     */
    public function setId(int $id): void;

    /**
     * Get the unique identifier for the user.
     *
     * @return int|null The user's unique ID, or null if not set.
     */
    public function getId(): ?int;

    /**
     * Get the name of the user.
     *
     * @return string The name of the user.
     */
    public function getName(): string;

    /**
     * Get the email address of the user.
     *
     * @return string The email address of the user.
     */
    public function getEmail(): string;

    /**
     * Get the role of the user.
     *
     * @return string The role of the user (e.g., 'student', 'teacher').
     */
    public function getRole(): string;
}
