<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Domain\Entities;

use Marcelofj\LibraryApp\Domain\ValueObjects\Email;
use Marcelofj\LibraryApp\Domain\Entities\Enums\UserRoleEnum;

/**
 * Class representing a teacher entity.
 *
 * This class extends the User class and represents a teacher with specific properties such as
 * the department. It also defines the role of the user as "Teacher".
 */
class Teacher extends User implements UserInterface
{
    /**
     * The role of the user, set to "Teacher".
     *
     * @var string
     */
    private string $role;

    /**
     * Constructor for the Teacher class.
     *
     * This constructor initializes a new Teacher object with the given name, email, and department.
     * It also calls the parent constructor (User) to set the name and email.
     * 
     * @param string $name The name of the teacher.
     * @param Email $email The email of the teacher.
     * @param string $department The department the teacher belongs to.
     */
    function __construct(protected string $name, protected Email $email, private string $department)
    {
        parent::__construct($name, $email);
        $this->role = UserRoleEnum::TEACHER->getValue();
    }

    /**
     * Gets the department of the teacher.
     *
     * @return string The department of the teacher.
     */
    public function getDepartment(): string
    {
        return $this->department;
    }

    /**
     * Gets the role of the user, which is "Teacher".
     *
     * @return string The role of the user.
     */
    function getRole(): string
    {
        return $this->role;
    }
}
