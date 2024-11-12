<?php

namespace Marcelofj\LibraryApp\Application;

use Marcelofj\LibraryApp\Domain\Entities\UserInterface;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\StudentRepositorySQLite;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\TeacherRepositorySQLite;

/**
 * Class UserApplication
 *
 * The UserApplication class handles the logic related to user management,
 * interacting with both the teacher and student repositories to retrieve user data.
 */
class UserApplication
{
    /**
     * Constructor for the UserApplication class.
     *
     * Initializes the repositories for teachers and students, which are used to
     * retrieve user information.
     *
     * @param TeacherRepositorySQLite $teacherRepo The repository responsible for teacher persistence.
     * @param StudentRepositorySQLite $studentRepo The repository responsible for student persistence.
     */
    public function __construct(private TeacherRepositorySQLite $teacherRepo, private StudentRepositorySQLite $studentRepo) {}

    /**
     * Retrieves a user by their ID.
     *
     * This method attempts to find the user in the teacher repository first. If not found,
     * it searches in the student repository.
     *
     * @param int $id The ID of the user to retrieve.
     *
     * @return UserInterface|null Returns an object implementing UserInterface if the user is found,
     *                            or null if the user is not found in either repository.
     */
    public function getUserById(int $id): ?UserInterface
    {
        $user = $this->teacherRepo->findById($id);
        if ($user) {
            return $user;
        }

        return $this->studentRepo->findById($id);
    }
}
