<?php

use Marcelofj\LibraryApp\Services\StudentService;
use Marcelofj\LibraryApp\Domain\Repositories\StudentRepository;
use Marcelofj\LibraryApp\Domain\Entities\Student;
use Marcelofj\LibraryApp\Domain\ValueObjects\Email;
use Mockery;

/**
 * Set up the test environment before each test.
 *
 * This function mocks the `StudentRepository` and initializes the `StudentService`
 * with the mocked repository for testing purposes.
 *
 * @beforeEach
 * @return void
 */
beforeEach(function () {
    $this->studentRepositoryMock = Mockery::mock(StudentRepository::class);
    $this->studentService = new StudentService($this->studentRepositoryMock);
});

/**
 * Clean up after each test.
 * 
 * This function closes all Mockery mock objects after each test execution.
 *
 * @afterEach
 * @return void
 */
afterEach(function () {
    Mockery::close();
});

/**
 * Test adding a new student.
 *
 * This test verifies that the `addStudent` method in the `StudentService` class 
 * correctly calls the `save` method of the `StudentRepository` to persist 
 * the new student in the database. It ensures the student object is passed correctly 
 * to the repository and the `save` method is called once.
 *
 * @test
 * @return void
 */
test('should add a new student', function () {
    $student = new Student('Student Name', new Email('student@email.com'), 'Tenth Degree', 'Architecture School');

    $this->studentRepositoryMock->shouldReceive('save')
        ->once()
        ->with($student);

    $this->studentService->addStudent($student);
});

/**
 * Test listing all students.
 *
 * This test checks that the `listStudents` method in the `StudentService` class 
 * correctly fetches all students by calling the `findAll` method of the `StudentRepository` 
 * and returns the list of students.
 *
 * @test
 * @return void
 */
test('should list all students', function () {
    $students = [
        new Student('Student Name', new Email('student@email.com'), 'Tenth Degree', 'Architecture School'),
        new Student('Student 2 Name', new Email('student2@email.com'), 'Fourth Degree', 'Law School')
    ];

    $this->studentRepositoryMock->shouldReceive('findAll')
        ->once()
        ->andReturn($students);
    $result = $this->studentService->listStudents();

    expect($result)->toEqual($students);
});

/**
 * Test getting a student by ID.
 *
 * This test ensures that the `getStudentById` method in the `StudentService` 
 * correctly calls the `findById` method of the `StudentRepository` and 
 * returns the student with the specified ID.
 *
 * @test
 * @return void
 */
test('should get a student by id', function () {
    $studentId = 1;
    $student = new Student('Student Name', new Email('student@email.com'), 'Tenth Degree', 'Architecture School');

    $this->studentRepositoryMock->shouldReceive('findById')
        ->once()
        ->with($studentId)
        ->andReturn($student);

    $result = $this->studentService->getStudentById($studentId);

    expect($result)->toEqual($student);
});

/**
 * Test deleting a student by ID.
 *
 * This test ensures that the `deleteStudent` method in the `StudentService` class 
 * correctly calls the `deleteById` method of the `StudentRepository` to delete 
 * a student by its ID. It verifies that the student is deleted successfully 
 * by checking the return value.
 *
 * @test
 * @return void
 */
test('should delete a student by id', function () {
    $studentId = 1;

    $this->studentRepositoryMock->shouldReceive('deleteById')
        ->once()
        ->with($studentId)
        ->andReturn(true);

    $result = $this->studentService->deleteStudent($studentId);

    expect($result)->toBeTrue();
});
