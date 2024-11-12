<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Domain\Repositories;

use Marcelofj\LibraryApp\Domain\Entities\Student;

/**
 * StudentRepository Interface.
 *
 * Defines the methods required for handling persistence operations
 * related to the `Student` entity. Any implementation of this
 * interface should provide logic for saving, retrieving, and deleting
 * student records.
 */
interface StudentRepository
{
    /**
     * Saves a student record to the repository.
     *
     * @param Student $student The student entity to be saved.
     * 
     * @return void
     */
    public function save(Student $student): void;

    /**
     * Retrieves all student records from the repository.
     *
     * @return Student[] An array of `Student` entities.
     */
    public function findAll(): array;

    /**
     * Retrieves a student record by its ID.
     *
     * Fetches a specific student from the repository using its unique identifier.
     *
     * @param int $id The ID of the student to be retrieved.
     * @return Student|null Returns the student if found, or null if not found.
     */
    public function findById(int $id): ?Student;

    /**
     * Deletes a student record from the repository by its unique identifier.
     *
     * @param int $id The unique identifier of the student to delete.
     * 
     * @return bool Returns true if the deletion was successful, false otherwise.
     */
    public function deleteById(int $id): bool;
}
