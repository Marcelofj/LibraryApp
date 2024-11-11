<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Domain\Entities;

use Marcelofj\LibraryApp\Domain\ValueObjects\Email;
use Marcelofj\LibraryApp\Domain\Entities\Enums\UserRoleEnum;

/**
 * Class representing a student entity.
 *
 * This class extends the User class and represents a student with specific properties such as
 * grade level and course. It also defines the role of the user as "Student".
 */
class Student extends User implements UserInterface
{
    /**
     * The role of the user, which is set to "Student".
     *
     * @var string
     */
    private string $role;

    /**
     * Constructor for the Student class.
     *
     * This constructor initializes a new Student object with the given name, email, grade level, 
     * and course. It also calls the parent constructor (User) to set the name and email.
     * 
     * @param string $name The name of the student.
     * @param Email $email The email of the student.
     * @param string $gradeLevel The grade level of the student.
     * @param string $course The course the student is enrolled in.
     */
    function __construct(protected string $name, protected Email $email, private string $gradeLevel, private string $course)
    {
        parent::__construct($name, $email);
        $this->role = UserRoleEnum::STUDENT->getValue();
    }

    /**
     * Gets the grade level of the student.
     *
     * @return string The grade level of the student.
     */
    public function getGradeLevel(): string
    {
        return $this->gradeLevel;
    }

    /**
     * Gets the course the student is enrolled in.
     *
     * @return string The course the student is enrolled in.
     */
    public function getCourse(): string
    {
        return $this->course;
    }

    /**
     * Gets the role of the user, which is "Student".
     *
     * @return string The role of the user.
     */
    function getRole(): string
    {
        return $this->role;
    }
}
