<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Services;

use Marcelofj\LibraryApp\Domain\Repositories\StudentRepository;
use Marcelofj\LibraryApp\Domain\Entities\Student;

/**
 * StudentService class
 * 
 * This service class handles the business logic related to student operations, 
 * such as adding, listing, and deleting students. It acts as a layer between 
 * the controllers and the repository, enforcing business rules if necessary.
 */
class StudentService
{
    /**
     * Constructor for the StudentService class.
     * 
     * @param StudentRepository $studentRepository The repository responsible for student persistence.
     */
    public function __construct(private StudentRepository $studentRepository) {}

    /**
     * Adds a new student to the repository.
     * 
     * @param Student $student The student entity to be added.
     */
    function addStudent(Student $student): void
    {
        $this->studentRepository->save($student);
    }

    /**
     * Lists all students from the repository.
     * 
     * @return array An array of all Student entities.
     */
    function listStudents(): array
    {
        return $this->studentRepository->findAll();
    }

    /**
     * Get a student by its ID.
     *
     * This method retrieves a specific student from the repository based on their ID.
     *
     * @param int $id The ID of the student to retrieve.
     * @return Student|null The student entity associated with the provided ID, or null if not found.
     */
    function getStudentById(int $id): ?Student
    {
        return $this->studentRepository->findById($id);
    }

    /**
     * Deletes a student by ID.
     * 
     * This method attempts to delete a student from the repository by their unique identifier.
     * 
     * @param int $id The ID of the student to be deleted.
     * 
     * @return bool Returns true if the student was successfully deleted, false if not found.
     */
    function deleteStudent(int $id): bool
    {
        return $this->studentRepository->deleteById($id);
    }
}
