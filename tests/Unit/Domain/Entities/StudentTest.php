<?php

use Marcelofj\LibraryApp\Domain\Entities\Student;
use Marcelofj\LibraryApp\Domain\ValueObjects\Email;
use Marcelofj\LibraryApp\Domain\Entities\Enums\UserRoleEnum;

/**
 * Sets up necessary instances before each test.
 */
beforeEach(function () {
    $this->studentEmail = new Email('estudante@email.com');
    $this->student = new Student('João da Silva', $this->studentEmail, 'second year', 'Mathematics');
});

/**
 * Tests that the student initializes with the correct name.
 *
 * @return void
 */
test('student initializes with correct name', function () {
    expect($this->student->getName())->toBe('João da Silva');
});

/**
 * Tests that the student initializes with the correct email.
 *
 * @return void
 */
test('student initializes with correct email', function () {
    expect($this->student->getEmail())->toBe('estudante@email.com');
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
 * Tests that the student initializes with the correct grade level.
 *
 * @return void
 */
test('student initializes with correct grade level', function () {
    expect($this->student->getGradeLevel())->toBe('second year');
});

/**
 * Tests that the student initializes with the correct course.
 *
 * @return void
 */
test('student initializes with correct course', function () {
    expect($this->student->getCourse())->toBe('Mathematics');
});

/**
 * Tests that the student initializes with the role as "Student".
 *
 * @return void
 */
test('student initializes with role as Student', function () {
    expect($this->student->getRole())->toBe(UserRoleEnum::STUDENT->getValue());
});

/**
 * Tests that the student's ID can be set and retrieved.
 *
 * @return void
 */
test('student can set and get id', function () {
    $this->student->setId(1);
    expect($this->student->getId())->toBe(1);
});
