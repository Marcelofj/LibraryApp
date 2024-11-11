<?php

use Marcelofj\LibraryApp\Domain\Entities\Enums\BookLoanStatusEnum;

/**
 * Test case for verifying valid book loan statuses.
 * 
 * This test checks that the `isValid` method of the `BookLoanStatusEnum` returns `true`
 * for known valid statuses: 'active', 'returned', and 'overdue'.
 *
 * @test
 * @return void
 */
it('verifies if a valid status returns true', function () {
    expect(BookLoanStatusEnum::isValid('active'))->toBeTrue();
    expect(BookLoanStatusEnum::isValid('returned'))->toBeTrue();
    expect(BookLoanStatusEnum::isValid('overdue'))->toBeTrue();
});

/**
 * Test case for verifying invalid book loan statuses.
 * 
 * This test checks that the `isValid` method of the `BookLoanStatusEnum` returns `false`
 * for invalid statuses: 'pending', 'lost', and 'completed'.
 *
 * @test
 * @return void
 */
it('verifies if an invalid status returns false', function () {
    expect(BookLoanStatusEnum::isValid('pending'))->toBeFalse();
    expect(BookLoanStatusEnum::isValid('lost'))->toBeFalse();
    expect(BookLoanStatusEnum::isValid('completed'))->toBeFalse();
});
