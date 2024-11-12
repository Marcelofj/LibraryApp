# LibraryApp

LibraryApp is a web application for managing library book loans. This project provides an API for handling book loan operations, including adding loans, listing all loans, fetching specific loan details, updating loan status, and deleting loans. Built with PHP, it leverages Mockery for testing, and follows PSR standards for HTTP messaging.

## Features - examples

- **Add Book Loans**: Create new loan entries for books.
- **List All Loans**: Retrieve a list of all book loans.
- **Fetch Loan Details by ID**: Get specific loan details using its unique ID.
- **Update Loan Status**: Modify the status of a loan, e.g., set it as returned.
- **Delete Book Loans**: Remove a loan entry by ID.
- **Fetch Active Loans**: Retrieve only active (ongoing) loans.

## Tech Stack

- **PHP**: Main language for backend logic.
- **Mockery**: For mocking and testing services.
- **Pest**: For tests.
- **Kint**: For dump.
- **Slim**:  PHP micro-framework.

## Installation

1. Clone the repository:

 ```console
 git clone https://github.com/Marcelofj/LibraryApp.git
 cd LibraryApp
 ```

2. Install dependencies:

 ```console
composer install
 ```

3. initialize database:

 ```console
composer init-db
 ```

## Testing 

LibraryApp includes a series of tests to ensure core functionality is maintained:

1. To run tests, execute:

 ```console
composer run-test
 ```

2. The tests are located in the tests directory, covering key functionalities like adding, fetching, updating, and deleting loans.

## API 

Endpoints

| Method | Endpoint        | Description                    |
|--------|-----------------|--------------------------------|
| POST   | /books          | Add a new book                 |
| GET    | /books          | List all books                 |
| GET    | /books/{id}     | Fetch a specific book by ID    |
| DELETE | /books/{id}     | Delete a book by ID            |
| POST   | /students       | Add a new student              |
| GET    | /students       | List all students              |
| GET    | /students/{id}  | Fetch a specific student by ID |
| DELETE | /students/{id}  | Delete a student by ID         |
| POST   | /teachers       | Add a new teacher              |
| GET    | /teachers       | List all teachers              |
| GET    | /teachers/{id}  | Fetch a specific teacher by ID |
| DELETE | /teachers/{id}  | Delete a teacherc by ID        |
| POST   | /loans          | Add a new book loan            |
| GET    | /loans          | List all book loans            |
| GET    | /loans/{id}     | Fetch a specific loan by ID    |
| PATCH  | /loans-status   | Update loan status             |
| GET    | /loans-active   | List all active book loans     |
| DELETE | /loans/{id}     | Delete a book loan by ID       |
| POST   | /loans-checkout | Create a book loan checkout    |
| POST   | /loans-checkin  | Create a book loan checkin     |

Example Usage

Add a New Loan

 ```console
POST /loans
Content-Type: application/json

{
	"book_id": 1,
	"user_id": 2,
	"loan_date": "2024-11-01 15:30:00",
	"due_date": "2024-11-09 15:30:00"
}
 ```

List All Loans

 ```console
GET /loans
 ```

Fetch Loan by ID

 ```console
GET /loans/1
 ```

Update Loan Status

 ```console
PATCH /loans-status
Content-Type: application/json

{
	"id": 1,
	"status": "returned"
}
 ```

Delete a Loan

 ```console
DELETE /loans/1
 ```

## Further Information

- I included my Insomnia collection - app_library_insomnia_collection.json - in this repository to facilitate the use of the API.

- To delete the database, run:

 ```console
composer delete-db
 ```

## License

This project is licensed under the MIT License. See the LICENSE file for more information.
Contributing

Contributions are welcome! If you would like to make any changes, please fork the repository and create a pull request.
Contact

For questions or suggestions, feel free to open an issue or contact the repository maintainer.

