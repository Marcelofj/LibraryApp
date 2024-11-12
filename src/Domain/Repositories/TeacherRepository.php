<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Domain\Repositories;

use Marcelofj\LibraryApp\Domain\Entities\Teacher;

/**
 * teacherRepository Interface
 *
 * Defines the methods required for handling persistence operations
 * related to the `teacher` entity. Any implementation of this
 * interface should provide logic for saving, retrieving, and deleting
 * teacher records.
 */
interface TeacherRepository
{
    /**
     * Saves a teacher record to the repository.
     *
     * @param teacher $teacher The teacher entity to be saved.
     * 
     * @return void
     */
    public function save(Teacher $teacher): void;

    /**
     * Retrieves all teacher records from the repository.
     *
     * @return teacher[] An array of `teacher` entities.
     */
    public function findAll(): array;

    /**
     * Retrieves a teacher record by its ID.
     *
     * Fetches a specific teacher from the repository using its unique identifier.
     *
     * @param int $id The ID of the teacher to be retrieved.
     * @return Teacher|null Returns the teacher if found, or null if not found.
     */
    public function findById(int $id): ?Teacher;

    /**
     * Deletes a teacher record from the repository by its unique identifier.
     *
     * @param int $id The unique identifier of the teacher to delete.
     * 
     * @return bool Returns true if the deletion was successful, false otherwise.
     */
    public function deleteById(int $id): bool;
}
