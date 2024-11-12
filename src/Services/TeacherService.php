<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Services;

use Marcelofj\LibraryApp\Domain\Repositories\TeacherRepository;
use Marcelofj\LibraryApp\Domain\Entities\Teacher;

/**
 * TeacherService class
 * 
 * This service class handles the business logic related to Teacher operations, 
 * such as adding, listing, and deleting Teachers. It acts as a layer between 
 * the controllers and the repository, enforcing business rules if necessary.
 */
class TeacherService
{
    /**
     * Constructor for the TeacherService class.
     * 
     * @param TeacherRepository $TeacherRepository The repository responsible for Teacher persistence.
     */
    public function __construct(private TeacherRepository $teacherRepository) {}

    /**
     * Adds a new Teacher to the repository.
     * 
     * @param Teacher $Teacher The Teacher entity to be added.
     */
    function addTeacher(Teacher $teacher): void
    {
        $this->teacherRepository->save($teacher);
    }

    /**
     * Lists all Teachers from the repository.
     * 
     * @return array An array of all Teacher entities.
     */
    function listTeachers(): array
    {
        return $this->teacherRepository->findAll();
    }

    /**
     * Get a teacher by its ID.
     *
     * This method retrieves a specific teacher from the repository based on their ID.
     *
     * @param int $id The ID of the teacher to retrieve.
     * @return Teacher|null The teacher entity associated with the provided ID, or null if not found.
     */
    function getTeacherById(int $id): ?Teacher
    {
        return $this->teacherRepository->findById($id);
    }

    /**
     * Deletes a Teacher by ID.
     * 
     * This method attempts to delete a Teacher from the repository by their unique identifier.
     * 
     * @param int $id The ID of the Teacher to be deleted.
     * 
     * @return bool Returns true if the Teacher was successfully deleted, false if not found.
     */
    function deleteTeacher(int $id): bool
    {
        return $this->teacherRepository->deleteById($id);
    }
}
