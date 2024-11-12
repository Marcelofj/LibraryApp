<?php

use Marcelofj\LibraryApp\Services\TeacherService;
use Marcelofj\LibraryApp\Domain\Repositories\TeacherRepository;
use Marcelofj\LibraryApp\Domain\Entities\Teacher;
use Marcelofj\LibraryApp\Domain\ValueObjects\Email;
use Mockery;

/**
 * Set up the test environment before each test.
 *
 * This function mocks the `TeacherRepository` and initializes the `TeacherService`
 * with the mocked repository for testing purposes.
 *
 * @beforeEach
 * @return void
 */
beforeEach(function () {
    $this->teacherRepositoryMock = Mockery::mock(TeacherRepository::class);
    $this->teacherService = new TeacherService($this->teacherRepositoryMock);
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
 * Test adding a new teacher.
 *
 * This test verifies that the `addTeacher` method in the `TeacherService` class 
 * correctly calls the `save` method of the `TeacherRepository` to persist 
 * the new teacher in the database. It ensures the teacher object is passed correctly 
 * to the repository and the `save` method is called once.
 *
 * @test
 * @return void
 */
test('should add a new teacher', function () {
    $teacher = new Teacher('Teacher Name', new Email('teacher@email.com'), 'Department 1');

    $this->teacherRepositoryMock->shouldReceive('save')
        ->once()
        ->with($teacher);

    $this->teacherService->addTeacher($teacher);
});

/**
 * Test listing all teachers.
 *
 * This test checks that the `listTeachers` method in the `TeacherService` class 
 * correctly fetches all teachers by calling the `findAll` method of the `TeacherRepository` 
 * and returns the list of teachers.
 *
 * @test
 * @return void
 */
test('should list all teachers', function () {
    $teachers = [
        new Teacher('Teacher Name', new Email('teacher@email.com'), 'Department 1'),
        new Teacher('Teacher 2 Name', new Email('teacher2@email.com'), 'Department 2')
    ];

    $this->teacherRepositoryMock->shouldReceive('findAll')
        ->once()
        ->andReturn($teachers);
    $result = $this->teacherService->listTeachers();

    expect($result)->toEqual($teachers);
});

/**
 * Test getting a teacher by ID.
 *
 * This test ensures that the `getTeacherById` method in the `TeacherService` 
 * correctly calls the `findById` method of the `TeacherRepository` and 
 * returns the teacher with the specified ID.
 *
 * @test
 * @return void
 */
test('should get a teacher by id', function () {
    $teacherId = 1;
    $teacher = new Teacher('Teacher Name', new Email('teacher@email.com'), 'Department 1');

    $this->teacherRepositoryMock->shouldReceive('findById')
        ->once()
        ->with($teacherId)
        ->andReturn($teacher);

    $result = $this->teacherService->getTeacherById($teacherId);

    expect($result)->toEqual($teacher);
});

/**
 * Test deleting a teacher by ID.
 *
 * This test ensures that the `deleteTeacher` method in the `TeacherService` class 
 * correctly calls the `deleteById` method of the `TeacherRepository` to delete 
 * a teacher by its ID. It verifies that the teacher is deleted successfully 
 * by checking the return value.
 *
 * @test
 * @return void
 */
test('should delete a teacher by id', function () {
    $teacherId = 1;

    $this->teacherRepositoryMock->shouldReceive('deleteById')
        ->once()
        ->with($teacherId)
        ->andReturn(true);

    $result = $this->teacherService->deleteTeacher($teacherId);

    expect($result)->toBeTrue();
});
