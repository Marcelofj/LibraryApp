<?php

use Marcelofj\LibraryApp\Domain\Entities\Student;
use Marcelofj\LibraryApp\Domain\ValueObjects\Email;
use Marcelofj\LibraryApp\Services\StudentService;
use Marcelofj\LibraryApp\Infra\Framework\Http\Controller\StudentController;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Marcelofj\LibraryApp\Domain\Entities\Enums\UserRoleEnum;

/**
 * Setup the mock objects for each test.
 *
 * @beforeEach
 */
beforeEach(function () {
    $this->studentService = \Mockery::mock(StudentService::class);
    $this->controller = new StudentController($this->studentService);
    $this->request = \Mockery::mock(ServerRequestInterface::class);
    $this->response = \Mockery::mock(ResponseInterface::class);
});

/**
 * Test that a book is added successfully and a success response is returned.
 * 
 * This test verifies that when a book is added using the BookController's addBook method,
 * the response body contains a success message and the status code is 200.
 *
 * @return void
 */
test('should add a student and return a success response', function () {
    $this->request
        ->shouldReceive('getBody->getContents')
        ->andReturn(json_encode(['name' => 'João da Silva', 'email' => 'email@email.com.br', 'grade_level' => 'fist year', 'course' => 'Law School']));

    $this->response
        ->shouldReceive('getBody->write')
        ->with(json_encode(['status' => 'Student added successfully']));

    $this->response
        ->shouldReceive('withHeader')
        ->with('Content-Type', 'application/json')
        ->andReturnSelf();

    $this->studentService
        ->shouldReceive('addStudent')
        ->once();

    $response = $this->controller->addStudent($this->request, $this->response);

    expect($response)->toBeInstanceOf(ResponseInterface::class);
});

/**
 * Test that all books are returned as a JSON response.
 * 
 * This test ensures that when the getAllBooks method is called, the books are fetched 
 * and returned as a JSON array with the correct properties.
 *
 * @return void
 */
test('should return all students as JSON response', function () {
    $students = [
        new Student('Maria', new Email('email@email.com.br'), 'fist year', 'Law School', UserRoleEnum::STUDENT->getValue()),
        new Student('Danilo', new Email('outroemail@email.com.br'), 'second year', 'Technology', UserRoleEnum::STUDENT->getValue())
    ];

    $this->studentService
        ->shouldReceive('listStudents')
        ->once()
        ->andReturn($students);

    $stream = \Mockery::mock('Psr\Http\Message\StreamInterface');
    $this->response
        ->shouldReceive('getBody')
        ->once()
        ->andReturn($stream);

    $stream->shouldReceive('write')
        ->once()
        ->with(json_encode([
            ['id' => null, 'name' => 'Maria', 'email' => 'email@email.com.br', 'grade_level' => 'fist year', 'course' => 'Law School', 'role' => UserRoleEnum::STUDENT->getValue()],
            ['id' => null, 'name' => 'Danilo', 'email' => 'outroemail@email.com.br', 'grade_level' => 'second year', 'course' => 'Technology', 'role' => UserRoleEnum::STUDENT->getValue()],
        ]));

    $this->response->shouldReceive('withHeader')
        ->once()
        ->andReturnSelf();

    $this->response->shouldReceive('getHeaderLine')
        ->once()
        ->with('Content-Type')
        ->andReturn('application/json');

    $response = $this->controller->getAllStudents($this->request, $this->response);

    expect($response->getHeaderLine('Content-Type'))->toBe('application/json');

    \Mockery::getContainer()->mockery_verify();
});

/**
 * Test that a book is deleted successfully and a success response is returned.
 * 
 * This test verifies that the deleteBook method returns the appropriate response 
 * when a book is successfully deleted.
 *
 * @return void
 */
test('should delete a student and return a success response', function () {
    $studentId = 3;

    $this->request->shouldReceive('getAttribute')->once()->with('id')->andReturn($studentId);

    $this->studentService
        ->shouldReceive('deleteStudent')
        ->once()
        ->with($studentId)
        ->andReturn(true);

    $stream = Mockery::mock(Psr\Http\Message\StreamInterface::class);
    $this->response->shouldReceive('getBody')->once()->andReturn($stream);
    $stream->shouldReceive('write')->once()->with(json_encode(['status' => 'Student deleted successfully']));

    $this->response->shouldReceive('withHeader')
        ->once()
        ->with('Content-Type', 'application/json')
        ->andReturn($this->response);

    $this->response->shouldReceive('withStatus')
        ->once()
        ->with(200)
        ->andReturn($this->response);

    $this->response->shouldReceive('getHeaderLine')
        ->once()
        ->with('Content-Type')
        ->andReturn('application/json');

    $this->response->shouldReceive('getStatusCode')
        ->once()
        ->andReturn(200);

    $response = $this->controller->deleteStudent($this->request, $this->response);

    expect($response->getHeaderLine('Content-Type'))->toBe('application/json');
    expect($response->getStatusCode())->toBe(200);
});

/**
 * Test that a "not found" response is returned if the book has already been deleted.
 * 
 * This test verifies that if the book to be deleted does not exist, 
 * a 404 response with a "Book not found" message is returned.
 *
 * @return void
 */
test('should return not found if student is already deleted', function () {
    $studentId = 9999;

    $this->request->shouldReceive('getAttribute')->once()->with('id')->andReturn($studentId);

    $this->studentService
        ->shouldReceive('deleteStudent')
        ->once()
        ->with($studentId)
        ->andReturn(false);

    $stream = \Mockery::mock(Psr\Http\Message\StreamInterface::class);
    $this->response->shouldReceive('getBody')->once()->andReturn($stream);

    $stream->shouldReceive('write')->once()->with(json_encode(['status' => 'Student not found']));

    $this->response->shouldReceive('withHeader')
        ->once()
        ->with('Content-Type', 'application/json')
        ->andReturn($this->response);

    $this->response->shouldReceive('getHeaderLine')
        ->once()
        ->with('Content-Type')
        ->andReturn('application/json');

    $this->response->shouldReceive('getStatusCode')
        ->once()
        ->andReturn(404);

    $this->response->shouldReceive('withStatus')
        ->once()
        ->with(404)
        ->andReturn($this->response);

    $response = $this->controller->deleteStudent($this->request, $this->response);

    expect($response->getHeaderLine('Content-Type'))->toBe('application/json');
    expect($response->getStatusCode())->toBe(404);

    \Mockery::getContainer()->mockery_verify();
});
