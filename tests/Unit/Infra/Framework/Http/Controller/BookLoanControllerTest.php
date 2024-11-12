<?php

use Mockery;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Marcelofj\LibraryApp\Services\BookLoanService;
use Marcelofj\LibraryApp\Infra\Framework\Http\Controller\BookLoanController;
use Marcelofj\LibraryApp\Domain\Entities\BookLoan;

/**
 * Sets up mock objects before each test.
 *
 * This function prepares the necessary mock objects for the tests, specifically mocking
 * the `BookLoanService` class and the `BookLoanController`.
 *
 * @beforeEach
 * @return void
 */
beforeEach(function () {
    $this->bookLoanService = Mockery::mock(BookLoanService::class);
    $this->controller = new BookLoanController($this->bookLoanService);
});

/**
 * Cleans up mock objects after each test.
 *
 * This function closes the mockery objects after each test to ensure no memory leaks.
 *
 * @afterEach
 * @return void
 */
afterEach(function () {
    Mockery::close();
});

/**
 * Verifies that a new book loan can be added.
 *
 * This test ensures that when a valid request for adding a book loan is made, the controller
 * processes it correctly by interacting with the `BookLoanService` and returns a success response.
 *
 * @test
 * @return void
 */
test('should add a new book loan', function () {
    $request = Mockery::mock(ServerRequestInterface::class);
    $response = Mockery::mock(ResponseInterface::class);
    $data = [
        'book_id' => 1,
        'user_id' => 1,
        'loan_date' => '2024-11-11',
        'due_date' => '2024-11-18'
    ];

    $request->shouldReceive('getBody->getContents')->andReturn(json_encode($data));
    $this->bookLoanService->shouldReceive('addBookLoan')->once();

    $response->shouldReceive('getBody->write')->with(json_encode(['status' => 'Loan added successfully']));
    $response->shouldReceive('withHeader')->with('Content-Type', 'application/json')->andReturnSelf();

    $result = $this->controller->addBookLoan($request, $response);
    expect($result)->toBe($response);
});

/**
 * Verifies that the controller lists all book loans.
 *
 * This test ensures that the `listLoanBooks` method returns all book loans correctly
 * by interacting with the `BookLoanService` and returning a list of loans.
 *
 * @test
 * @return void
 */
test('should list all book loans', function () {
    $request = Mockery::mock(ServerRequestInterface::class);
    $response = Mockery::mock(ResponseInterface::class);

    $bookLoan = new BookLoan(1, 1, new DateTime('2024-11-11'), new DateTime('2024-11-18'));
    $bookLoan->setId(1);

    $this->bookLoanService->shouldReceive('listLoanBooks')->andReturn([$bookLoan]);

    $response->shouldReceive('getBody->write')->with(json_encode([[
        'id' => 1,
        'book_id' => 1,
        'user_id' => 1,
        'loan_date' => '2024-11-11 00:00:00',
        'due_date' => '2024-11-18 00:00:00',
        'return_date' => null,
        'status' => 'active'
    ]]));
    $response->shouldReceive('withHeader')->with('Content-Type', 'application/json')->andReturnSelf();

    $result = $this->controller->listLoanBooks($request, $response);
    expect($result)->toBe($response);
});

/**
 * Verifies that a book loan can be fetched by its ID.
 *
 * This test ensures that when a valid book loan ID is provided, the controller retrieves
 * the correct book loan information from the `BookLoanService`.
 *
 * @test
 * @return void
 */
test('should fetch a loan book by id', function () {
    $request = Mockery::mock(ServerRequestInterface::class);
    $response = Mockery::mock(ResponseInterface::class);

    $bookLoan = new BookLoan(1, 1, new DateTime('2024-11-11'), new DateTime('2024-11-18'));
    $bookLoan->setId(1);

    $request->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $this->bookLoanService->shouldReceive('fetchLoanBookById')->with(1)->andReturn($bookLoan);

    $response->shouldReceive('getBody->write')->with(json_encode([
        'id' => 1,
        'book_id' => 1,
        'user_id' => 1,
        'loan_date' => '2024-11-11 00:00:00',
        'due_date' => '2024-11-18 00:00:00',
        'return_date' => null,
        'status' => 'active'
    ]));
    $response->shouldReceive('withHeader')->with('Content-Type', 'application/json')->andReturnSelf();
    $response->shouldReceive('withStatus')->with(200)->andReturnSelf();

    $result = $this->controller->fetchLoanBookById($request, $response);
    expect($result)->toBe($response);
});

/**
 * Verifies that the status of a book loan can be updated.
 *
 * This test ensures that when a valid request to update the status of a book loan is made,
 * the controller correctly interacts with the `BookLoanService` and updates the status.
 *
 * @test
 * @return void
 */
test('should update loan book status', function () {
    $request = Mockery::mock(ServerRequestInterface::class);
    $response = Mockery::mock(ResponseInterface::class);
    $data = [
        'id' => 1,
        'status' => 'returned'
    ];

    $request->shouldReceive('getBody->getContents')->andReturn(json_encode($data));
    $this->bookLoanService->shouldReceive('updateLoanBookStatus')->with(1, 'returned')->andReturn(true);

    $response->shouldReceive('getBody->write')->with(json_encode(['message' => 'Loan status updated successfully.']));
    $response->shouldReceive('withStatus')->with(200)->andReturnSelf();
    $response->shouldReceive('withHeader')->with('Content-Type', 'application/json')->andReturnSelf();

    $result = $this->controller->updateLoanBookStatus($request, $response);
    expect($result)->toBe($response);
});

/**
 * Verifies that the controller fetches all active book loans.
 *
 * This test ensures that the `fetchActiveLoanBooks` method correctly returns only active loan books.
 *
 * @test
 * @return void
 */
test('should fetch all active loan books', function () {
    $request = Mockery::mock(ServerRequestInterface::class);
    $response = Mockery::mock(ResponseInterface::class);

    $bookLoan = new BookLoan(1, 1, new DateTime('2024-11-11'), new DateTime('2024-11-18'));
    $bookLoan->setId(1);

    $this->bookLoanService->shouldReceive('fetchActiveLoanBooks')->andReturn([$bookLoan]);

    $response->shouldReceive('getBody->write')->with(json_encode([[
        'id' => 1,
        'book_id' => 1,
        'user_id' => 1,
        'loan_date' => '2024-11-11 00:00:00',
        'due_date' => '2024-11-18 00:00:00',
        'status' => 'active'
    ]]));
    $response->shouldReceive('withHeader')->with('Content-Type', 'application/json')->andReturnSelf();
    $response->shouldReceive('withStatus')->with(200)->andReturnSelf();

    $result = $this->controller->fetchActiveLoanBooks($request, $response);
    expect($result)->toBe($response);
});

/**
 * Verifies that a book loan can be deleted.
 *
 * This test ensures that when a valid request to delete a book loan is made, the controller
 * processes it correctly and interacts with the `BookLoanService` to delete the loan.
 *
 * @test
 * @return void
 */
test('should delete a book loan', function () {
    $request = Mockery::mock(ServerRequestInterface::class);
    $response = Mockery::mock(ResponseInterface::class);

    $request->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $this->bookLoanService->shouldReceive('deleteBookLoan')->with(1)->andReturn(true);

    $response->shouldReceive('getBody->write')->with(json_encode(['status' => 'Loan deleted successfully']));
    $response->shouldReceive('withHeader')->with('Content-Type', 'application/json')->andReturnSelf();
    $response->shouldReceive('withStatus')->with(200)->andReturnSelf();

    $result = $this->controller->deleteBookLoan($request, $response);
    expect($result)->toBe($response);
});

/**
 * Verifies that the controller handles errors when trying to delete a non-existing book loan.
 *
 * This test ensures that when a book loan is not found for deletion, the controller
 * correctly returns a 404 error response.
 *
 * @test
 * @return void
 */
test('should return an error if book loan not found', function () {
    $request = Mockery::mock(ServerRequestInterface::class);
    $response = Mockery::mock(ResponseInterface::class);

    $request->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $this->bookLoanService->shouldReceive('deleteBookLoan')->with(1)->andReturn(false);

    $response->shouldReceive('getBody->write')->with(json_encode(['status' => 'Loan not found']));
    $response->shouldReceive('withHeader')->with('Content-Type', 'application/json')->andReturnSelf();
    $response->shouldReceive('withStatus')->with(404)->andReturnSelf();

    $result = $this->controller->deleteBookLoan($request, $response);
    expect($result)->toBe($response);
});
