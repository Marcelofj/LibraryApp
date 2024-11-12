<?php

use Mockery;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Marcelofj\LibraryApp\Application\BookLoanApplication;
use Marcelofj\LibraryApp\Infra\Framework\Http\Controller\BookLoanApplicationController;

/**
 * Sets up mock objects before each test.
 *
 * This function prepares the necessary mock objects for the tests, specifically mocking
 * the `BookLoanApplication` class and the `BookLoanApplicationController`.
 *
 * @beforeEach
 * @return void
 */
beforeEach(function () {
    $this->bookLoanApplication = Mockery::mock(BookLoanApplication::class);
    $this->controller = new BookLoanApplicationController($this->bookLoanApplication);
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
 * Verifies the creation of a new book checkout.
 *
 * This test ensures that when a valid request for a book checkout is made, the controller
 * processes it correctly by interacting with the `BookLoanApplication` service and
 * returns a success response with status code 201.
 *
 * @test
 * @return void
 */
test('should create a new book checkout', function () {
    $request = Mockery::mock(ServerRequestInterface::class);
    $response = Mockery::mock(ResponseInterface::class);
    $data = [
        'book_id' => 1,
        'user_id' => 1,
        'loan_date' => '2024-11-11',
        'due_date' => '2024-11-18'
    ];

    $request->shouldReceive('getBody->getContents')->andReturn(json_encode($data));
    $this->bookLoanApplication->shouldReceive('bookCheckout')->andReturn(true);

    $response->shouldReceive('getBody->write')->with(json_encode(['status' => 'success', 'message' => 'Book checkout created successfully.']));
    $response->shouldReceive('withStatus')->with(201)->andReturnSelf();
    $response->shouldReceive('withHeader')->with('Content-Type', 'application/json')->andReturnSelf();

    $result = $this->controller->bookCheckout($request, $response);
    expect($result)->toBe($response);
});

/**
 * Verifies that an error is returned when creating a book checkout with missing data.
 *
 * This test checks the behavior of the controller when the required data for a book checkout
 * is not provided in the request. It ensures that the controller returns an error response
 * with status code 422 and an appropriate error message.
 *
 * @test
 * @return void
 */
test('should return an error when creating book checkout with missing data', function () {
    $request = Mockery::mock(ServerRequestInterface::class);
    $response = Mockery::mock(ResponseInterface::class);
    $data = [];

    $request->shouldReceive('getBody->getContents')->andReturn(json_encode($data));

    $response->shouldReceive('getBody->write')->with(json_encode(['status' => 'error', 'message' => 'Required data missing.']));
    $response->shouldReceive('withStatus')->with(422)->andReturnSelf();
    $response->shouldReceive('withHeader')->with('Content-Type', 'application/json')->andReturnSelf();

    $result = $this->controller->bookCheckout($request, $response);
    expect($result)->toBe($response);
});

/**
 * Verifies the creation of a new book checkin.
 *
 * This test ensures that when a valid request for a book checkin is made, the controller
 * processes it correctly by interacting with the `BookLoanApplication` service and
 * returns a success response with status code 201.
 *
 * @test
 * @return void
 */
test('should create a new book checkin', function () {
    $request = Mockery::mock(ServerRequestInterface::class);
    $response = Mockery::mock(ResponseInterface::class);
    $data = [
        'book_id' => 1,
        'id' => 1,
        'return_date' => '2024-11-11'
    ];

    $request->shouldReceive('getBody->getContents')->andReturn(json_encode($data));
    $this->bookLoanApplication->shouldReceive('bookCheckin')->andReturn(true);

    $response->shouldReceive('getBody->write')->with(json_encode(['status' => 'success', 'message' => 'Book checkin created successfully.']));
    $response->shouldReceive('withStatus')->with(201)->andReturnSelf();
    $response->shouldReceive('withHeader')->with('Content-Type', 'application/json')->andReturnSelf();

    $result = $this->controller->bookCheckin($request, $response);
    expect($result)->toBe($response);
});

/**
 * Verifies that an error is returned when creating a book checkin with missing data.
 *
 * This test checks the behavior of the controller when the required data for a book checkin
 * is not provided in the request. It ensures that the controller returns an error response
 * with status code 422 and an appropriate error message.
 *
 * @test
 * @return void
 */
test('should return an error when creating book checkin with missing data', function () {
    $request = Mockery::mock(ServerRequestInterface::class);
    $response = Mockery::mock(ResponseInterface::class);
    $data = [];

    $request->shouldReceive('getBody->getContents')->andReturn(json_encode($data));

    $response->shouldReceive('getBody->write')->with(json_encode(['status' => 'error', 'message' => 'Required data missing.']));
    $response->shouldReceive('withStatus')->with(422)->andReturnSelf();
    $response->shouldReceive('withHeader')->with('Content-Type', 'application/json')->andReturnSelf();

    $result = $this->controller->bookCheckin($request, $response);
    expect($result)->toBe($response);
});
