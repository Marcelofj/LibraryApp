<?php

use Marcelofj\LibraryApp\Domain\Entities\Enums\UserRoleEnum;

/**
 * Test case for verifying the value of the STUDENT role.
 * 
 * This test checks that the `getValue` method of the `UserRoleEnum` correctly returns
 * the value associated with the `STUDENT` role, which should be 'student'.
 *
 * @test
 * @return void
 */
it('verifies that the correct value is returned for STUDENT role', function () {
    expect(UserRoleEnum::STUDENT->getValue())->toBe('student');
});

/**
 * Test case for verifying the value of the TEACHER role.
 * 
 * This test checks that the `getValue` method of the `UserRoleEnum` correctly returns
 * the value associated with the `TEACHER` role, which should be 'teacher'.
 *
 * @test
 * @return void
 */
it('verifies that the correct value is returned for TEACHER role', function () {
    expect(UserRoleEnum::TEACHER->getValue())->toBe('teacher');
});
