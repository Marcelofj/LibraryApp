<?php

use Marcelofj\LibraryApp\Domain\Entities\Book;
use Marcelofj\LibraryApp\Domain\ValueObjects\ISBN;
use Marcelofj\LibraryApp\Services\BookService;
use Marcelofj\LibraryApp\Infra\Framework\Http\Controller\BookController;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Setup the mock objects for each test.
 *
 * @beforeEach
 */
beforeEach(function () {
    $this->bookService = \Mockery::mock(BookService::class);
    $this->controller = new BookController($this->bookService);
    $this->request = \Mockery::mock(ServerRequestInterface::class);
    $this->response = \Mockery::mock(ResponseInterface::class);
});

/**
 * Test that a book is added successfully and a success response is returned.
 *
 * @return void
 */
test('should add a book and return a success response', function () {
    $this->request
        ->shouldReceive('getBody->getContents')
        ->andReturn(json_encode(['isbn' => '978-3161484100', 'title' => 'Test Book', 'author' => 'Author Test']));

    $this->response
        ->shouldReceive('getBody->write')
        ->with(json_encode(['status' => 'Book added successfully']));

    $this->response
        ->shouldReceive('withHeader')
        ->with('Content-Type', 'application/json')
        ->andReturnSelf();

    $this->bookService
        ->shouldReceive('addBook')
        ->once();

    $response = $this->controller->addBook($this->request, $this->response);

    expect($response)->toBeInstanceOf(ResponseInterface::class);
});

/**
 * Test that all books are returned as a JSON response.
 *
 * @return void
 */
test('should return all books as JSON response', function () {
    $books = [
        new Book('Title 1', 'Author 1', new ISBN('123-4567890123')),
        new Book('Title 2', 'Author 2', new ISBN('987-6543210987'))
    ];

    $this->bookService
        ->shouldReceive('listBooks')
        ->once()
        ->andReturn($books);

    $stream = \Mockery::mock('Psr\Http\Message\StreamInterface');
    $this->response
        ->shouldReceive('getBody')
        ->once()
        ->andReturn($stream);

    $stream->shouldReceive('write')
        ->once()
        ->with(json_encode([
            ['id' => null, 'title' => 'Title 1', 'author' => 'Author 1', 'isbn' => '123-4567890123', 'isAvailable' => true],
            ['id' => null, 'title' => 'Title 2', 'author' => 'Author 2', 'isbn' => '987-6543210987', 'isAvailable' => true]
        ]));

    $this->response->shouldReceive('withHeader')
        ->once()
        ->andReturnSelf();

    $this->response->shouldReceive('getHeaderLine')
        ->once()
        ->with('Content-Type')
        ->andReturn('application/json');

    $response = $this->controller->getAllBooks($this->request, $this->response);

    expect($response->getHeaderLine('Content-Type'))->toBe('application/json');

    \Mockery::getContainer()->mockery_verify();
});

/**
 * Test that a book is deleted successfully and a success response is returned.
 *
 * @return void
 */
test('should delete a book and return a success response', function () {
    $bookId = 3;

    $this->request->shouldReceive('getAttribute')->once()->with('id')->andReturn($bookId);

    $this->bookService
        ->shouldReceive('deleteBook')
        ->once()
        ->with($bookId)
        ->andReturn(true);

    $stream = Mockery::mock(Psr\Http\Message\StreamInterface::class);
    $this->response->shouldReceive('getBody')->once()->andReturn($stream);
    $stream->shouldReceive('write')->once()->with(json_encode(['status' => 'Book deleted successfully']));

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

    $response = $this->controller->deleteBook($this->request, $this->response);

    expect($response->getHeaderLine('Content-Type'))->toBe('application/json');
    expect($response->getStatusCode())->toBe(200);
});

/**
 * Test that a "not found" response is returned if the book has already been deleted.
 *
 * @return void
 */
test('should return not found if book is already deleted', function () {
    $bookId = 9999;

    $this->request->shouldReceive('getAttribute')->once()->with('id')->andReturn($bookId);

    $this->bookService
        ->shouldReceive('deleteBook')
        ->once()
        ->with($bookId)
        ->andReturn(false);

    $stream = \Mockery::mock(Psr\Http\Message\StreamInterface::class);
    $this->response->shouldReceive('getBody')->once()->andReturn($stream);

    $stream->shouldReceive('write')->once()->with(json_encode(['status' => 'Book not found']));

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

    $response = $this->controller->deleteBook($this->request, $this->response);

    expect($response->getHeaderLine('Content-Type'))->toBe('application/json');
    expect($response->getStatusCode())->toBe(404);

    \Mockery::getContainer()->mockery_verify();
});
