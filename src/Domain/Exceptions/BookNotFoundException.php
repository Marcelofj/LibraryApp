<?php

namespace Marcelofj\LibraryApp\Domain\Exceptions;

use Exception;

/**
 * Exception thrown when a book is not found in the system.
 *
 * This exception is used when a requested book cannot be located in the library system,
 * either because the book does not exist or has been removed from the system.
 */
class BookNotFoundException extends Exception
{
    /**
     * @var string $message The exception message.
     * The default message is 'Book not found', indicating that the requested book
     * could not be found in the library system.
     */
    protected $message = 'Book not found';
}
