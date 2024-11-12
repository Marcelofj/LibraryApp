<?php

use Marcelofj\LibraryApp\Domain\Entities\Teacher;
use Marcelofj\LibraryApp\Domain\ValueObjects\Email;
use Marcelofj\LibraryApp\Services\TeacherService;
use Marcelofj\LibraryApp\Infra\Framework\Http\Controller\TeacherController;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Marcelofj\LibraryApp\Domain\Entities\Enums\UserRoleEnum;

/**
 * Setup the mock objects for each test.
 *
 * @beforeEach
 */
beforeEach(function () {
    $this->teacherService = \Mockery::mock(TeacherService::class);
    $this->controller = new TeacherController($this->teacherService);
    $this->request = \Mockery::mock(ServerRequestInterface::class);
    $this->response = \Mockery::mock(ResponseInterface::class);
});

/**
 * Test the method to ensure it adds a teacher and returns a success response.
 *
 * @test
 */
test('should add a teacher and returnc a success response', function () {
    $this->request
        ->shouldReceive('getBody->getContents')
        ->andReturn(json_encode(['name' => 'Teacher Name', 'email' => 'teacher@email.com', 'department' => 'Department 1']));

    $this->response
        ->shouldReceive('getBody->write')
        ->with(json_encode(['status' => 'Teacher added successfully']));

    $this->response
        ->shouldReceive('withHeader')
        ->with('Content-Type', 'application/json')
        ->andReturnSelf();

    $this->teacherService
        ->shouldReceive('addTeacher')
        ->once();

    $response = $this->controller->addTeacher($this->request, $this->response);

    expect($response)->toBeInstanceOf(ResponseInterface::class);
});

/**
 * Test the  method to ensure it returns a list of teachers as a JSON response.
 *
 * @test
 */
test('should return all teachers as JSON response', function () {
    $teachers = [
        new Teacher('Teacher Name', new Email('teacher@email.com'), 'Department 1', UserRoleEnum::TEACHER->getValue()),
        new Teacher('Teacher 2 Name', new Email('teacher2@email.com'), 'Department 2', UserRoleEnum::TEACHER->getValue())
    ];

    $this->teacherService
        ->shouldReceive('listTeachers')
        ->once()
        ->andReturn($teachers);

    $stream = \Mockery::mock('Psr\Http\Message\StreamInterface');
    $this->response
        ->shouldReceive('getBody')
        ->once()
        ->andReturn($stream);

    $stream->shouldReceive('write')
        ->once()
        ->with(json_encode([
            ['id' => null, 'name' => 'Teacher Name', 'email' => 'teacher@email.com', 'department' => 'Department 1', 'role' => UserRoleEnum::TEACHER->getValue()],
            ['id' => null, 'name' => 'Teacher 2 Name', 'email' => 'teacher2@email.com', 'department' => 'Department 2', 'role' => UserRoleEnum::TEACHER->getValue()],
        ]));

    $this->response->shouldReceive('withHeader')
        ->once()
        ->andReturnSelf();

    $this->response->shouldReceive('getHeaderLine')
        ->once()
        ->with('Content-Type')
        ->andReturn('application/json');

    $response = $this->controller->getAllTeachers($this->request, $this->response);

    expect($response->getHeaderLine('Content-Type'))->toBe('application/json');

    \Mockery::getContainer()->mockery_verify();
});

/**
 * Test the deleteTeacher method to ensure it deletes a teacher and returns a success response.
 *
 * @test
 */
test('should delete a teacher and return a success response', function () {
    $teacherId = 3;

    $this->request->shouldReceive('getAttribute')->once()->with('id')->andReturn($teacherId);

    $this->teacherService
        ->shouldReceive('deleteTeacher')
        ->once()
        ->with($teacherId)
        ->andReturn(true);

    $stream = Mockery::mock(Psr\Http\Message\StreamInterface::class);
    $this->response->shouldReceive('getBody')->once()->andReturn($stream);
    $stream->shouldReceive('write')->once()->with(json_encode(['status' => 'Teacher deleted successfully']));

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

    $response = $this->controller->deleteTeacher($this->request, $this->response);

    expect($response->getHeaderLine('Content-Type'))->toBe('application/json');
    expect($response->getStatusCode())->toBe(200);
});

/**
 * Test the  method to return a not found response when the teacher doesn't exist.
 *
 * @test
 */
test('should return not found if teacher is already deleted', function () {
    $teacherId = 9999;

    $this->request->shouldReceive('getAttribute')->once()->with('id')->andReturn($teacherId);

    $this->teacherService
        ->shouldReceive('deleteTeacher')
        ->once()
        ->with($teacherId)
        ->andReturn(false);

    $stream = \Mockery::mock(Psr\Http\Message\StreamInterface::class);
    $this->response->shouldReceive('getBody')->once()->andReturn($stream);

    $stream->shouldReceive('write')->once()->with(json_encode(['status' => 'Teacher not found']));

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

    $response = $this->controller->deleteTeacher($this->request, $this->response);

    expect($response->getHeaderLine('Content-Type'))->toBe('application/json');
    expect($response->getStatusCode())->toBe(404);

    \Mockery::getContainer()->mockery_verify();
});
