<?php

use Mockery;
use Marcelofj\LibraryApp\Domain\Entities\Teacher;
use Marcelofj\LibraryApp\Domain\ValueObjects\Email;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\TeacherRepositorySQLite;
use Marcelofj\LibraryApp\Domain\Entities\Enums\UserRoleEnum;

/**
 * Set up the test environment before each test.
 * 
 * This function mocks the PDO instance and injects it into the TeacherRepositorySQLite instance.
 * It uses reflection to set the private `$pdo` property of the repository.
 *
 * @beforeEach
 * @return void
 */
beforeEach(function () {
    $this->pdo = Mockery::mock(PDO::class);
    $this->repository = new TeacherRepositorySQLite();

    $reflection = new ReflectionClass($this->repository);
    $property = $reflection->getProperty('pdo');
    $property->setAccessible(true);
    $property->setValue($this->repository, $this->pdo);
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
 * Test that a teacher can be saved.
 *
 * This test verifies that the `save` method of the `TeacherRepositorySQLite` class 
 * correctly inserts a teacher into the database. It checks that the necessary SQL queries 
 * are prepared and executed, including the insertion into both the `users` and `teachers` tables.
 *
 * @test
 * @return void
 */
test('should save a teacher', function () {
    $teacher = new Teacher('Teacher Name', new Email('teacher@example.com'), 'Department 1');

    $userStmt = Mockery::mock(PDOStatement::class);
    $userStmt->shouldReceive('bindValue')->times(3);
    $userStmt->shouldReceive('execute')->once();

    $this->pdo->shouldReceive('prepare')
        ->with('INSERT INTO users (name, email, role) VALUES (:name, :email, :role)')
        ->andReturn($userStmt);

    $teacherStmt = Mockery::mock(PDOStatement::class);
    $teacherStmt->shouldReceive('bindValue')->times(2);
    $teacherStmt->shouldReceive('execute')->once();

    $this->pdo->shouldReceive('prepare')
        ->with('INSERT INTO teachers (user_id, department) VALUES (:user_id, :department)')
        ->andReturn($teacherStmt);

    $this->pdo->shouldReceive('lastInsertId')
        ->andReturn('1');

    $this->repository->save($teacher);
});

/**
 * Test that all teachers can be retrieved.
 *
 * This test verifies that the `findAll` method of the `TeacherRepositorySQLite` class 
 * correctly retrieves all teachers from the database by performing a SQL query 
 * and mapping the results to `Teacher` objects.
 *
 * @test
 * @return void
 */
test('should retrieve all teachers', function () {
    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('bindValue')->with(':role', UserRoleEnum::TEACHER->getValue(), PDO::PARAM_STR);
    $stmt->shouldReceive('execute')->once();
    $stmt->shouldReceive('fetch')
        ->andReturn(
            ['id' => 1, 'name' => 'Teacher Name', 'email' => 'teacher@example.com', 'department' => 'Department 1'],
            ['id' => 2, 'name' => 'Teacher 2 Name', 'email' => 'teacher2@example.com', 'department' => 'Department 2'],
            false
        );

    $this->pdo->shouldReceive('prepare')
        ->with('SELECT u.id, u.name, u.email, t.department 
                  FROM users u
                  JOIN teachers t ON u.id = t.user_id
                  WHERE u.role = :role')
        ->andReturn($stmt);

    $teachers = $this->repository->findAll();

    expect($teachers)->toHaveCount(2);
    expect($teachers[0])->toBeInstanceOf(Teacher::class);
    expect($teachers[0]->getName())->toBe('Teacher Name');
    expect($teachers[1]->getName())->toBe('Teacher 2 Name');
});

/**
 * Test that a teacher can be found by ID.
 *
 * This test verifies that the `findById` method correctly retrieves a teacher by its ID.
 * If the teacher is not found, it should return `null`.
 *
 * @test
 * @return void
 */
test('should find a teacher by id', function () {
    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('bindValue')->with(':id', 1, PDO::PARAM_INT);
    $stmt->shouldReceive('execute')->once();
    $stmt->shouldReceive('fetch')
        ->andReturn(
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john.doe@example.com', 'department' => 'Math']
        );

    $this->pdo->shouldReceive('prepare')
        ->with('SELECT u.id, u.name, u.email, t.department 
                  FROM users u
                  JOIN teachers t ON u.id = t.user_id
                  WHERE t.user_id = :id')
        ->andReturn($stmt);

    $teacher = $this->repository->findById(1);

    expect($teacher)->toBeInstanceOf(Teacher::class);
    expect($teacher->getName())->toBe('John Doe');
});

/**
 * Test that a teacher can be deleted by ID.
 *
 * This test verifies that the `deleteById` method of the `TeacherRepositorySQLite` class 
 * correctly deletes a teacher from the database. It checks that the appropriate 
 * SQL DELETE statement is prepared and executed, and the method returns `true` 
 * if the teacher is successfully deleted.
 *
 * @test
 * @return void
 */
test('should delete a teacher by id', function () {
    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('bindValue')->with(':id', 1, PDO::PARAM_INT)->once();
    $stmt->shouldReceive('bindValue')->with(':role', UserRoleEnum::TEACHER->getValue(), PDO::PARAM_STR)->once();
    $stmt->shouldReceive('execute')->once()->andReturnTrue();
    $stmt->shouldReceive('rowCount')->andReturn(1);

    $this->pdo->shouldReceive('prepare')
        ->with('DELETE FROM users WHERE id = :id AND role = :role')
        ->andReturn($stmt);

    $deleted = $this->repository->deleteById(1);

    expect($deleted)->toBeTrue();
});
