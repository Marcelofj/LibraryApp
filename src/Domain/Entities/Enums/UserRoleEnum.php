<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Domain\Entities\Enums;

/**
 * Enum UserRoleEnum
 *
 * This enum represents the possible roles of a user in the system. It is used
 * to define the role of a user, such as student or teacher.
 */
enum UserRoleEnum: string
{
    /**
     * The user is a student in the system.
     */
    case STUDENT = 'student';

    /**
     * The user is a teacher in the system.
     */
    case TEACHER = 'teacher';

    /**
     * Gets the string value of the user role.
     *
     * This method returns the string value associated with the enum case, such as
     * 'student' or 'teacher'.
     *
     * @return string The string value of the user role.
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
