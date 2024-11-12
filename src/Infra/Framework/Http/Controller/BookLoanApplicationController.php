<?php

namespace Marcelofj\LibraryApp\Infra\Framework\Http\Controller;

use Marcelofj\LibraryApp\Application\BookLoanApplication;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * BookLoanApplicationController class
 *
 * This controller is responsible for handling HTTP requests related to book loans, including:
 * - Checking out a book (bookCheckout)
 * - Checking in a book (bookCheckin)
 *
 * The controller interacts with the `BookLoanApplication` service to perform the necessary business logic
 * for managing the book loan process.
 */
class BookLoanApplicationController
{
    /**
     * Constructor for the BookLoanApplicationController.
     *
     * Initializes the controller with the `BookLoanApplication` service to handle book loan operations.
     *
     * @param BookLoanApplication $bookLoanApplication The service responsible for handling book loan operations.
     */
    public function __construct(private BookLoanApplication $bookLoanApplication) {}

    /**
     * Handles the request to check out a book.
     *
     * This method receives the request data (in JSON format), processes the checkout information, 
     * and delegates the book checkout to the `BookLoanApplication` service.
     * It expects the following fields in the request body:
     * - book_id: The ID of the book being checked out.
     * - user_id: The ID of the user borrowing the book.
     * - loan_date: The date when the book is being borrowed.
     * - due_date: The due date for the return of the book.
     * 
     * If the book is successfully checked out, a success message is returned. Otherwise, an error message is returned.
     * 
     * @param ServerRequestInterface $request The HTTP request object.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response with the result of the operation.
     */
    public function bookCheckout(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);

        if (isset($data['book_id'], $data['user_id'], $data['loan_date'], $data['due_date'])) {
            try {
                $loanDate = new \DateTime($data['loan_date']);
                $dueDate = new \DateTime($data['due_date']);

                $isBorrowed = $this->bookLoanApplication->bookCheckout(
                    (int)$data['book_id'],
                    (int)$data['user_id'],
                    $loanDate,
                    $dueDate
                );

                if ($isBorrowed) {
                    $response->getBody()->write(json_encode(['status' => 'success', 'message' => 'Book checkout created successfully.']));
                    return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
                }
            } catch (\Exception $e) {
                $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'Error:' . $e->getMessage()]));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }
        }

        $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'Required data missing.']));
        return $response->withStatus(422)->withHeader('Content-Type', 'application/json');
    }

    /**
     * Handles the request to check in a book.
     *
     * This method receives the request data (in JSON format), processes the check-in information, 
     * and delegates the book check-in to the `BookLoanApplication` service.
     * It expects the following fields in the request body:
     * - book_id: The ID of the book being checked in.
     * - id: The ID of the book loan.
     * - return_date: The date when the book is being returned.
     * 
     * If the book is successfully checked in, a success message is returned. Otherwise, an error message is returned.
     * 
     * @param ServerRequestInterface $request The HTTP request object.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response with the result of the operation.
     */
    public function bookCheckin(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        $data = json_decode($request->getBody()->getContents(), true);

        if (isset($data['book_id'], $data['id'], $data['return_date'])) {

            try {
                $returnDate = new \DateTime($data['return_date']);

                $isReturned = $this->bookLoanApplication->bookCheckin(
                    (int)$data['book_id'],
                    (int)$data['id'],
                    $returnDate,
                );

                if ($isReturned) {
                    $response->getBody()->write(json_encode(['status' => 'success', 'message' => 'Book checkin created successfully.']));
                    return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
                }
            } catch (\Exception $e) {
                $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'Error:' . $e->getMessage()]));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }
        }

        $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'Required data missing.']));
        return $response->withStatus(422)->withHeader('Content-Type', 'application/json');
    }
}
