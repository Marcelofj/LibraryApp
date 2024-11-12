<?php

namespace Marcelofj\LibraryApp\Domain\Exceptions;

use Exception;

/**
 * Exception thrown when a book has not been returned.
 *
 * This exception is used when an action is attempted on a book that has not yet been
 * returned to the library, such as trying to check it out again or perform an operation
 * that requires the book to be returned first.
 */
class BookNotReturnedException extends Exception
{
    /**
     * @var string $message The exception message.
     * The default message is 'Book has not yet returned', indicating that the book
     * cannot be processed because it has not been returned yet.
     */
    protected $message = 'Book has not yet returned';
}
