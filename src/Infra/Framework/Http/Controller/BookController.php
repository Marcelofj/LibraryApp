<?php

namespace Marcelofj\LibraryApp\Infra\Framework\Http\Controller;

use Marcelofj\LibraryApp\Services\BookService;
use Marcelofj\LibraryApp\Domain\Entities\Book;
use Marcelofj\LibraryApp\Domain\ValueObjects\ISBN;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * BookController class
 *
 * This controller handles HTTP requests related to the book entity.
 * It defines the logic for adding, retrieving, and deleting books.
 */
class BookController
{
    /**
     * Constructor for the BookController.
     *
     * @param BookService $bookService The service responsible for handling the book-related operations.
     */
    public function __construct(private BookService $bookService) {}

    /**
     * Handles the request to add a new book.
     *
     * This method receives the request data (JSON), creates a Book object, 
     * and delegates the addition of the book to the BookService.
     * 
     * @param ServerRequestInterface $request The HTTP request object.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response with the result of the operation.
     */
    public function addBook(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);
        $isbn = new ISBN($data['isbn']);
        $book = new Book($data['title'], $data['author'], $isbn);
        $this->bookService->addBook($book);
        $response->getBody()->write(json_encode(['status' => 'Book added successfully']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Handles the request to retrieve all books.
     *
     * This method retrieves all books from the BookService, formats the data,
     * and returns it as a JSON response.
     * 
     * @param ServerRequestInterface $request The HTTP request object.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response containing a JSON list of books.
     */
    public function getAllBooks(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $books = $this->bookService->listBooks();
        $booksData = array_map(function (Book $book) {
            return [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'isbn' => $book->getIsbn(),
                'isAvailable' => $book->getStatus(),
            ];
        }, $books);
        $response->getBody()->write(json_encode($booksData));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Handles the request to retrieve a book by its ID.
     *
     * This method retrieves a specific book from the `BookService` using its ID.
     * If the book is found, it returns the book's data in JSON format; otherwise,
     * it returns an error message indicating that the book was not found.
     * 
     * @param ServerRequestInterface $request The HTTP request object.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response containing the book's data or an error message.
     */
    public function getBookById(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $book = $this->bookService->getBookById($id);

        if ($book) {
            $response->getBody()->write(json_encode([
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'isbn' => $book->getIsbn(),
                'isAvailable' => $book->getStatus(),
            ]));
        } else {
            $response->getBody()->write(json_encode(['error' => 'Book not found']));
        }

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    /**
     * Handles the request to delete a book by its ID.
     *
     * This method attempts to delete a book using its ID, which is provided
     * in the URL. If the deletion is successful, a success message is returned.
     * Otherwise, a not found message is returned.
     * 
     * @param ServerRequestInterface $request The HTTP request object.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response with a status indicating success or failure.
     */
    public function deleteBook(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $isDeleted = $this->bookService->deleteBook($id);

        if ($isDeleted) {
            $response->getBody()->write(json_encode(['status' => 'Book deleted successfully']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }

        $response->getBody()->write(json_encode(['status' => 'Book not found']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
}
