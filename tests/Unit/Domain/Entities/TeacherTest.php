<?php

use Marcelofj\LibraryApp\Domain\Entities\Teacher;
use Marcelofj\LibraryApp\Domain\ValueObjects\Email;
use Marcelofj\LibraryApp\Domain\Entities\Enums\UserRoleEnum;

/**
 * Sets up necessary instances before each test.
 */
beforeEach(function () {
    $this->teacherEmail = new Email('teacher@example.com');
    $this->teacher = new Teacher('Maria da Silva', $this->teacherEmail, 'Science Department');
});

/**
 * Tests that the teacher initializes with the correct name.
 *
 * @return void
 */
test('teacher initializes with correct name', function () {
    expect($this->teacher->getName())->toBe('Maria da Silva');
});


/**
 * Tests that the teacher initializes with the correct email.
 *
 * @return void
 */
test('teacher initializes with correct email', function () {
    expect($this->teacher->getEmail())->toBe('teacher@example.com');
});

/**
 * Tests that an exception is thrown for an invalid email format.
 *
 * @return void
 */
test('throws exception for invalid email format', function () {
    try {
        new Email('invalid-email');
    } catch (InvalidArgumentException $e) {
        expect($e->getMessage())->toBe('Invalid e-mail!');
    }
});

/**
 * Tests that the teacher initializes with the correct department.
 *
 * @return void
 */
test('teacher initializes with correct department', function () {
    expect($this->teacher->getDepartment())->toBe('Science Department');
});

/**
 * Tests that the teacher initializes with the role as "Teacher".
 *
 * @return void
 */
test('teacher initializes with role as Teacher', function () {
    expect($this->teacher->getRole())->toBe(UserRoleEnum::TEACHER->getValue());
});

/**
 * Tests that the teacher's ID can be set and retrieved.
 *
 * @return void
 */
test('teacher can set and get id', function () {
    $this->teacher->setId(2);
    expect($this->teacher->getId())->toBe(2);
});
