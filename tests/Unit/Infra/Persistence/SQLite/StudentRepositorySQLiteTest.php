<?php

use Mockery;
use Marcelofj\LibraryApp\Domain\Entities\Student;
use Marcelofj\LibraryApp\Domain\ValueObjects\Email;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\StudentRepositorySQLite;
use Marcelofj\LibraryApp\Domain\Entities\Enums\UserRoleEnum;

/**
 * Set up the test environment before each test.
 * 
 * This function mocks the PDO instance and injects it into the StudentRepositorySQLite instance.
 * It uses reflection to set the private `$pdo` property of the repository.
 *
 * @beforeEach
 * @return void
 */
beforeEach(function () {
    $this->pdo = Mockery::mock(PDO::class);
    $this->repository = new StudentRepositorySQLite();

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
 * Test that a student can be saved.
 *
 * This test verifies that the `save` method of the `StudentRepositorySQLite` class 
 * correctly inserts a student into the database. It checks that the necessary SQL queries 
 * are prepared and executed, including the insertion into both the `users` and `students` tables.
 *
 * @test
 * @return void
 */
test('should save a student', function () {
    $student = new Student('Student Name', new Email('student@example.com'), '10', 'Math');

    $userStmt = Mockery::mock(PDOStatement::class);
    $userStmt->shouldReceive('bindValue')->times(3);
    $userStmt->shouldReceive('execute')->once();

    $this->pdo->shouldReceive('prepare')
        ->with('INSERT INTO users (name, email, role) VALUES (:name, :email, :role)')
        ->andReturn($userStmt);

    $studentStmt = Mockery::mock(PDOStatement::class);
    $studentStmt->shouldReceive('bindValue')->times(3);
    $studentStmt->shouldReceive('execute')->once();

    $this->pdo->shouldReceive('prepare')
        ->with('INSERT INTO students (user_id, grade_level, course) VALUES (:user_id, :grade_level, :course)')
        ->andReturn($studentStmt);

    $this->pdo->shouldReceive('lastInsertId')
        ->andReturn('1');

    $this->repository->save($student);
});

/**
 * Test that all students can be retrieved.
 *
 * This test verifies that the `findAll` method of the `StudentRepositorySQLite` class 
 * correctly retrieves all students from the database by performing a SQL query 
 * and mapping the results to `Student` objects.
 *
 * @test
 * @return void
 */
test('should retrieve all students', function () {
    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('bindValue')->with(':role', UserRoleEnum::STUDENT->getValue(), PDO::PARAM_STR);
    $stmt->shouldReceive('execute')->once();
    $stmt->shouldReceive('fetch')
        ->andReturn(
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john.doe@example.com', 'grade_level' => 'Senior', 'course' => 'Computer Science'],
            ['id' => 2, 'name' => 'Jane Doe', 'email' => 'jane.doe@example.com', 'grade_level' => 'Junior', 'course' => 'Mathematics'],
            false
        );

    $this->pdo->shouldReceive('prepare')
        ->with('SELECT u.id, u.name, u.email, s.grade_level, s.course 
                  FROM users u
                  JOIN students s ON u.id = s.user_id
                  WHERE u.role = :role')
        ->andReturn($stmt);

    $students = $this->repository->findAll();

    expect($students)->toHaveCount(2);
    expect($students[0])->toBeInstanceOf(Student::class);
    expect($students[0]->getName())->toBe('John Doe');
    expect($students[1]->getName())->toBe('Jane Doe');
});

/**
 * Test that a student can be found by ID.
 *
 * This test verifies that the `findById` method correctly retrieves a student by its ID.
 * If the student is not found, it should return `null`.
 *
 * @test
 * @return void
 */
test('should find a student by id', function () {
    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('bindValue')->with(':id', 1, PDO::PARAM_INT);
    $stmt->shouldReceive('execute')->once();
    $stmt->shouldReceive('fetch')
        ->andReturn(
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john.doe@example.com', 'grade_level' => 'Senior', 'course' => 'Computer Science']
        );

    $this->pdo->shouldReceive('prepare')
        ->with('SELECT u.id, u.name, u.email, s.grade_level, s.course 
                  FROM users u
                  JOIN students s ON u.id = s.user_id
                  WHERE s.user_id = :id')
        ->andReturn($stmt);

    $student = $this->repository->findById(1);

    expect($student)->toBeInstanceOf(Student::class);
    expect($student->getName())->toBe('John Doe');
});

/**
 * Test that a student can be deleted by ID.
 *
 * This test verifies that the `deleteById` method of the `StudentRepositorySQLite` class 
 * correctly deletes a student from the database. It checks that the appropriate 
 * SQL DELETE statement is prepared and executed, and the method returns `true` 
 * if the student is successfully deleted.
 *
 * @test
 * @return void
 */
test('should delete a student by id', function () {
    // Criando o mock do PDOStatement
    $stmt = Mockery::mock(PDOStatement::class);
    $stmt->shouldReceive('bindValue')->with(':id', 1, PDO::PARAM_INT)->once();
    $stmt->shouldReceive('bindValue')->with(':role', UserRoleEnum::STUDENT->getValue(), PDO::PARAM_STR)->once();
    $stmt->shouldReceive('execute')->once()->andReturnTrue();
    $stmt->shouldReceive('rowCount')->andReturn(1);

    // Mockando a preparação da query para deletar o estudante
    $this->pdo->shouldReceive('prepare')
        ->with('DELETE FROM users WHERE id = :id AND role = :role')
        ->andReturn($stmt);

    // Chamando o método deleteById
    $deleted = $this->repository->deleteById(1);

    // Verificando se o estudante foi deletado com sucesso
    expect($deleted)->toBeTrue();
});
