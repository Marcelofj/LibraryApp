<?php

namespace Marcelofj\LibraryApp\Infra\Framework\Http\Controller;

use Marcelofj\LibraryApp\Services\BookLoanService;
use Marcelofj\LibraryApp\Domain\Entities\BookLoan;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * BookLoanController class
 *
 * This controller is responsible for managing the HTTP requests related to book loans.
 * It interacts with the `BookLoanService` to perform operations such as adding a new book loan,
 * listing book loans, fetching loan details, updating loan status, and deleting book loans.
 */
class BookLoanController
{
    /**
     * Constructor for the BookLoanController.
     *
     * Initializes the controller with the `BookLoanService` to handle the logic for book loan management.
     *
     * @param BookLoanService $bookLoanService The service responsible for managing book loan operations.
     */
    public function __construct(private BookLoanService $bookLoanService) {}

    /**
     * Handles the request to add a new book loan.
     *
     * This method extracts the book loan data from the request body, creates a `BookLoan` entity,
     * and passes it to the `BookLoanService` to add the loan.
     * 
     * @param ServerRequestInterface $request The HTTP request object containing the loan data.
     * @param ResponseInterface $response The HTTP response object to send back the result.
     * 
     * @return ResponseInterface The HTTP response with the result of the operation.
     */
    public function addBookLoan(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);
        $bookLoan = new BookLoan(
            $data['book_id'],
            $data['user_id'],
            new \DateTime($data['loan_date']),
            new \DateTime($data['due_date'])
        );
        $this->bookLoanService->addBookLoan($bookLoan);
        $response->getBody()->write(json_encode(['status' => 'Loan added successfully']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Handles the request to list all book loans.
     *
     * This method fetches all book loans from the `BookLoanService`, formats the data, and returns it in the response.
     * 
     * @param ServerRequestInterface $request The HTTP request object.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response containing the list of book loans.
     */
    public function listLoanBooks(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $loanBooks = $this->bookLoanService->listLoanBooks();
        $loanbooksData = array_map(function (BookLoan $bookLoan) {
            return [
                'id' => $bookLoan->getId(),
                'book_id' => $bookLoan->getBookId(),
                'user_id' => $bookLoan->getUserId(),
                'loan_date' => $bookLoan->getLoanDate()->format('Y-m-d H:i:s'),
                'due_date' => $bookLoan->getDueDate()->format('Y-m-d H:i:s'),
                'return_date' => $bookLoan->getReturnDate() ? $bookLoan->getReturnDate()->format('Y-m-d H:i:s') : null,
                'status' => $bookLoan->getStatus()
            ];
        }, $loanBooks);
        $response->getBody()->write(json_encode($loanbooksData));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Fetches a single book loan by its ID.
     *
     * This method looks up a book loan by its ID, formats the loan data, and returns it in the response.
     * If the loan is not found, an error message is returned.
     * 
     * @param ServerRequestInterface $request The HTTP request object containing the loan ID.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response containing the loan data or an error message.
     */
    public function fetchLoanBookById(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $bookLoan = $this->bookLoanService->fetchLoanBookById($id);

        if ($bookLoan) {
            $response->getBody()->write(json_encode([
                'id' => $bookLoan->getId(),
                'book_id' => $bookLoan->getBookId(),
                'user_id' => $bookLoan->getUserId(),
                'loan_date' => $bookLoan->getLoanDate()->format('Y-m-d H:i:s'),
                'due_date' => $bookLoan->getDueDate()->format('Y-m-d H:i:s'),
                'return_date' => $bookLoan->getReturnDate() ? $bookLoan->getReturnDate()->format('Y-m-d H:i:s') : null,
                'status' => $bookLoan->getStatus()
            ]));
        } else {
            $response->getBody()->write(json_encode(['error' => 'Loan not found']));
        }

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    /**
     * Updates the status of a book loan.
     *
     * This method updates the status of a specific book loan based on the ID and the new status provided in the request body.
     * If the update is successful, a success message is returned; otherwise, an error message is returned.
     * 
     * @param ServerRequestInterface $request The HTTP request object containing the loan ID and status.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response with the result of the operation.
     */
    public function updateLoanBookStatus(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);

        if (isset($data['status'])) {
            $id = (int) $data['id'];
            $status = $data['status'];

            $isUpdated = $this->bookLoanService->updateLoanBookStatus($id, $status);

            if ($isUpdated) {
                $response->getBody()->write(json_encode(['message' => 'Loan status updated successfully.']));
                return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
            } else {
                $response->getBody()->write(json_encode(['error' => 'Failed to update loan status.']));
                return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
            }
        } else {
            $response->getBody()->write(json_encode(['error' => 'Status is required.']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    /**
     * Fetches all active book loans.
     *
     * This method retrieves all book loans that are currently active and returns them in the response.
     * 
     * @param ServerRequestInterface $request The HTTP request object.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response containing the list of active book loans.
     */
    public function fetchActiveLoanBooks(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $activeBookLoans = $this->bookLoanService->fetchActiveLoanBooks();
        $loanbooksData = array_map(function (BookLoan $bookLoan) {
            return [
                'id' => $bookLoan->getId(),
                'book_id' => $bookLoan->getBookId(),
                'user_id' => $bookLoan->getUserId(),
                'loan_date' => $bookLoan->getLoanDate()->format('Y-m-d H:i:s'),
                'due_date' => $bookLoan->getDueDate()->format('Y-m-d H:i:s'),
                'status' => $bookLoan->getStatus()
            ];
        }, $activeBookLoans);
        $response->getBody()->write(json_encode($loanbooksData));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    /**
     * Deletes a specific book loan by its ID.
     *
     * This method deletes a book loan based on its ID. If the loan is successfully deleted, a success message is returned.
     * If the loan is not found, an error message is returned.
     * 
     * @param ServerRequestInterface $request The HTTP request object containing the loan ID.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response with the result of the operation.
     */
    public function deleteBookLoan(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $isDeleted = $this->bookLoanService->deleteBookLoan($id);

        if ($isDeleted) {
            $response->getBody()->write(json_encode(['status' => 'Loan deleted successfully']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }

        $response->getBody()->write(json_encode(['status' => 'Loan not found']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
}
