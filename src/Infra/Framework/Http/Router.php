<?php

namespace Marcelofj\LibraryApp\Infra\Framework\Http;

use Slim\App;
use Marcelofj\LibraryApp\Infra\Framework\Http\Controller\BookController;
use Marcelofj\LibraryApp\Infra\Framework\Http\Controller\StudentController;
use Marcelofj\LibraryApp\Infra\Framework\Http\Controller\TeacherController;
use Marcelofj\LibraryApp\Infra\Framework\Http\Controller\BookLoanController;
use Marcelofj\LibraryApp\Infra\Framework\Http\Controller\BookLoanApplicationController;

/**
 * Router class
 *
 * This class is responsible for setting up the routes for the application.
 * It maps HTTP requests (such as GET, POST, DELETE) to their corresponding controller actions.
 * The routes are defined using the Slim framework, which is a micro-framework for PHP.
 */
class Router
{
    /**
     * Constructor
     *
     * @param App $app The Slim app instance.
     */
    public function __construct(private App $app) {}

    /**
     * Set up the application routes.
     *
     * This method registers routes for handling resources operations.
     * 
     * The routes are mapped to methods in the BookController (or UserController, if uncommented).
     */
    public function setupRoutes(): void
    {
        // Books Routes
        $this->app->post('/books', [BookController::class, 'addBook']);
        $this->app->get('/books', [BookController::class, 'getAllBooks']);
        $this->app->get('/books/{id}', [BookController::class, 'getBookById']);
        $this->app->delete('/books/{id}', [BookController::class, 'deleteBook']);

        // Students Routes
        $this->app->post('/students', [StudentController::class, 'addStudent']);
        $this->app->get('/students', [StudentController::class, 'getAllStudents']);
        $this->app->get('/students/{id}', [StudentController::class, 'getStudentById']);
        $this->app->delete('/students/{id}', [StudentController::class, 'deleteStudent']);

        // Teachers Routes
        $this->app->post('/teachers', [TeacherController::class, 'addTeacher']);
        $this->app->get('/teachers', [TeacherController::class, 'getAllTeachers']);
        $this->app->get('/teachers/{id}', [TeacherController::class, 'getTeacherById']);
        $this->app->delete('/teachers/{id}', [TeacherController::class, 'deleteTeacher']);

        // BookLoan Routes
        $this->app->post('/loans', [BookLoanController::class, 'addBookLoan']);
        $this->app->get('/loans', [BookLoanController::class, 'listLoanBooks']);
        $this->app->get('/loans/{id}', [BookLoanController::class, 'fetchLoanBookById']);
        $this->app->patch('/loans-status', [BookLoanController::class, 'updateLoanBookStatus']);
        $this->app->get('/loans-active', [BookLoanController::class, 'fetchActiveLoanBooks']);
        $this->app->delete('/loans/{id}', [BookLoanController::class, 'deleteBookLoan']);

        // BookLoan Application Routes
        $this->app->post('/loans-checkout', [BookLoanApplicationController::class, 'bookCheckout']);
        $this->app->post('/loans-checkin', [BookLoanApplicationController::class, 'bookCheckin']);
    }
}
