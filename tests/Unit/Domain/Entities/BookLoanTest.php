<?php

use Marcelofj\LibraryApp\Domain\Entities\BookLoan;
use Marcelofj\LibraryApp\Domain\Entities\Enums\BookLoanStatusEnum;
use InvalidArgumentException;
use DateTime;

/**
 * Test case for throwing an exception when an invalid status is set.
 * 
 * This test checks that when an invalid status (not defined in the enum) is passed to the
 * `setStatus` method of the `BookLoan` class, an `InvalidArgumentException` is thrown.
 *
 * @test
 * @return void
 */
it('throws an exception for invalid status', function () {
    $bookLoan = new BookLoan(1, 1, new DateTime(), new DateTime());

    expect(fn() => $bookLoan->setStatus('invalid'))->toThrow(InvalidArgumentException::class);
});

/**
 * Test case for setting a valid status correctly.
 * 
 * This test checks that valid statuses (defined in the `BookLoanStatusEnum`) can be set correctly
 * for a `BookLoan` object, and the status is retrieved as expected.
 *
 * @test
 * @return void
 */
it('sets the status correctly for a valid status', function () {
    $bookLoan = new BookLoan(1, 1, new DateTime(), new DateTime());

    $bookLoan->setStatus(BookLoanStatusEnum::ACTIVE->value);
    expect($bookLoan->getStatus())->toBe(BookLoanStatusEnum::ACTIVE->value);

    $bookLoan->setStatus(BookLoanStatusEnum::RETURNED->value);
    expect($bookLoan->getStatus())->toBe(BookLoanStatusEnum::RETURNED->value);

    $bookLoan->setStatus(BookLoanStatusEnum::OVERDUE->value);
    expect($bookLoan->getStatus())->toBe(BookLoanStatusEnum::OVERDUE->value);
});

/**
 * Test case for setting and getting the ID of the BookLoan.
 * 
 * This test verifies that the `setId` and `getId` methods work correctly for the `BookLoan` class.
 *
 * @test
 * @return void
 */
it('sets and gets the id correctly', function () {
    $bookLoan = new BookLoan(1, 1, new DateTime(), new DateTime());

    $bookLoan->setId(123);
    expect($bookLoan->getId())->toBe(123);
});

/**
 * Test case for getting the book ID from a BookLoan.
 * 
 * This test ensures that the `getBookId` method returns the correct book ID associated with the loan.
 *
 * @test
 * @return void
 */
it('gets the book id correctly', function () {
    $bookLoan = new BookLoan(123, 1, new DateTime(), new DateTime());

    expect($bookLoan->getBookId())->toBe(123);
});

/**
 * Test case for getting the user ID from a BookLoan.
 * 
 * This test checks that the `getUserId` method returns the correct user ID associated with the loan.
 *
 * @test
 * @return void
 */
it('gets the user id correctly', function () {
    $bookLoan = new BookLoan(1, 456, new DateTime(), new DateTime());

    expect($bookLoan->getUserId())->toBe(456);
});

/**
 * Test case for getting the loan date from a BookLoan.
 * 
 * This test verifies that the `getLoanDate` method returns the correct loan date, and is an instance of `DateTime`.
 *
 * @test
 * @return void
 */
it('gets the loan date correctly', function () {
    $loanDate = new DateTime();
    $bookLoan = new BookLoan(1, 1, $loanDate, new DateTime());

    expect($bookLoan->getLoanDate())->toBeInstanceOf(DateTime::class);
    expect($bookLoan->getLoanDate())->toEqual($loanDate);
});

/**
 * Test case for getting the due date from a BookLoan.
 * 
 * This test ensures that the `getDueDate` method returns the correct due date, and is an instance of `DateTime`.
 *
 * @test
 * @return void
 */
it('gets the due date correctly', function () {
    $dueDate = new DateTime('+7 days');
    $bookLoan = new BookLoan(1, 1, new DateTime(), $dueDate);

    expect($bookLoan->getDueDate())->toBeInstanceOf(DateTime::class);
    expect($bookLoan->getDueDate())->toEqual($dueDate);
});

/**
 * Test case for getting the return date from a BookLoan when set.
 * 
 * This test verifies that the `getReturnDate` method returns the correct return date when set using the `setReturnDate` method.
 *
 * @test
 * @return void
 */
it('gets the return date correctly when set', function () {
    $returnDate = new DateTime('+2 days');
    $bookLoan = new BookLoan(1, 1, new DateTime(), new DateTime());
    $bookLoan->setReturnDate($returnDate);

    expect($bookLoan->getReturnDate())->toBeInstanceOf(DateTime::class);
    expect($bookLoan->getReturnDate())->toEqual($returnDate);
});

/**
 * Test case for getting the return date from a BookLoan when not set.
 * 
 * This test ensures that the `getReturnDate` method returns `null` when no return date is set.
 *
 * @test
 * @return void
 */
it('gets the return date correctly when not set', function () {
    $bookLoan = new BookLoan(1, 1, new DateTime(), new DateTime());

    expect($bookLoan->getReturnDate())->toBeNull();
});
