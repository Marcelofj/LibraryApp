<?php

declare(strict_types=1);

use Mockery;
use Marcelofj\LibraryApp\Application\UserApplication;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\TeacherRepositorySQLite;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\StudentRepositorySQLite;
use Marcelofj\LibraryApp\Domain\Entities\Teacher;
use Marcelofj\LibraryApp\Domain\Entities\Student;
use Marcelofj\LibraryApp\Domain\ValueObjects\Email;

/**
 * Clean up after each test.
 * 
 * This function ensures that Mockery closes after each test, cleaning up the mocks
 * and preventing memory leaks or unexpected behaviors in subsequent tests.
 *
 * @afterEach
 * @return void
 */
beforeEach(function () {
    $this->teacherRepository = Mockery::mock(TeacherRepositorySQLite::class);
    $this->studentRepository = Mockery::mock(StudentRepositorySQLite::class);
    $this->userApplication = new UserApplication($this->teacherRepository, $this->studentRepository);
});

/**
 * Clean up after each test.
 * 
 * This function closes all Mockery mock objects.
 *
 * @afterEach
 * @return void
 */
afterEach(function () {
    Mockery::close();
});

/**
 * Test case for getting a teacher by ID.
 * 
 * This test verifies that the `getUserById` method correctly retrieves a teacher entity
 * from the repository using the provided teacher ID.
 * It mocks the `findById` method of the TeacherRepositorySQLite to return a teacher entity.
 *
 * @test
 * @return void
 */
test('should return a teacher by id', function () {
    $teacherId = 1;
    $teacher = new Teacher('John Doe', new Email('john.doe@example.com'), 'Mathematics');
    $teacher->setId($teacherId);

    $this->teacherRepository->shouldReceive('findById')->with($teacherId)->andReturn($teacher);
    $this->studentRepository->shouldReceive('findById')->with($teacherId)->andReturn(null);

    $result = $this->userApplication->getUserById($teacherId);

    expect($result)->toBe($teacher);
});

/**
 * Test case for getting a student by ID.
 * 
 * This test checks that the `getUserById` method correctly retrieves a student entity
 * from the repository when a student ID is provided.
 * The `findById` method of the StudentRepositorySQLite is mocked to return a student entity.
 *
 * @test
 * @return void
 */
test('should return a student by id', function () {
    $studentId = 1;
    $student = new Student('Jane Doe', new Email('jane.doe@example.com'), 'Senior', 'Physics');
    $student->setId($studentId);

    $this->teacherRepository->shouldReceive('findById')->with($studentId)->andReturn(null);
    $this->studentRepository->shouldReceive('findById')->with($studentId)->andReturn($student);

    $result = $this->userApplication->getUserById($studentId);

    expect($result)->toBe($student);
});

/**
 * Test case for handling user not found.
 * 
 * This test ensures that if neither a teacher nor a student is found for the provided user ID,
 * the `getUserById` method returns null.
 *
 * @test
 * @return void
 */
test('should return null if user not found', function () {
    $userId = 1;

    $this->teacherRepository->shouldReceive('findById')->with($userId)->andReturn(null);
    $this->studentRepository->shouldReceive('findById')->with($userId)->andReturn(null);

    $result = $this->userApplication->getUserById($userId);

    expect($result)->toBeNull();
});
