<?php

namespace Marcelofj\LibraryApp\Application;

use Marcelofj\LibraryApp\Infra\Persistence\SQLite\TeacherRepositorySQLite;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\StudentRepositorySQLite;

/**
 * Class UserApplicationFactory
 *
 * The UserApplicationFactory class is responsible for creating an instance of the
 * UserApplication class by providing the necessary dependencies, specifically
 * the teacher and student repositories.
 */
class UserApplicationFactory
{
    /**
     * Creates a new instance of the UserApplication class.
     *
     * This method serves as a factory for creating a UserApplication object.
     * It takes in the teacher and student repositories as parameters and returns
     * a new instance of UserApplication.
     *
     * @param TeacherRepositorySQLite $teacherRepository The repository responsible for managing teacher data.
     * @param StudentRepositorySQLite $studentRepository The repository responsible for managing student data.
     *
     * @return UserApplication A new instance of the UserApplication class.
     */
    public static function create(
        TeacherRepositorySQLite $teacherRepository,
        StudentRepositorySQLite $studentRepository
    ): UserApplication {
        return new UserApplication($teacherRepository, $studentRepository);
    }
}
