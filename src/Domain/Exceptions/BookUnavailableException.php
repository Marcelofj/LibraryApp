<?php

namespace Marcelofj\LibraryApp\Domain\Exceptions;

use Exception;

/**
 * Exception thrown when a book is unavailable.
 *
 * This exception is used when a book is unavailable for checkout or any other operation
 * that requires the book to be in an available state, such as when the book is already 
 * checked out or has been marked as unavailable in the system.
 */
class BookUnavailableException extends Exception
{
    /**
     * @var string $message The exception message.
     * The default message is 'Book is currently unavailable', indicating that the book
     * cannot be processed because it is not in an available state.
     */
    protected $message = 'Book is currently unavailable';
}
