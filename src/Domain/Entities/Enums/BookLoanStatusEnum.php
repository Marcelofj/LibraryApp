<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Domain\Entities\Enums;

/**
 * Enum BookLoanStatusEnum
 *
 * This enum represents the possible statuses of a book loan. It is used to
 * track the current state of a book loan throughout its lifecycle.
 */
enum BookLoanStatusEnum: string
{
    /**
     * The loan is currently active and the book has not been returned yet.
     */
    case ACTIVE = 'active';

    /**
     * The loan has been completed and the book has been returned.
     */
    case RETURNED = 'returned';

    /**
     * The loan is overdue and the book has not been returned by the due date.
     */
    case OVERDUE = 'overdue';

    /**
     * Checks if a given status is a valid book loan status.
     *
     * This method checks if a provided status string corresponds to one of the
     * valid statuses defined in the enum. It ensures that only recognized status
     * values are considered valid.
     *
     * @param string $status The status to check.
     *
     * @return bool Returns true if the status is valid, otherwise false.
     */
    public static function isValid(string $status): bool
    {
        return in_array($status, array_map(fn($case) => $case->value, self::cases()), true);
    }
}
