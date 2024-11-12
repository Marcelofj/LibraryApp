<?php

use DI\Container;
use Marcelofj\LibraryApp\Infra\Framework\Http\ContainerConfig;
use Marcelofj\LibraryApp\Infra\Framework\Http\Controller\BookController;
use Marcelofj\LibraryApp\Infra\Framework\Http\Controller\StudentController;
use Marcelofj\LibraryApp\Infra\Framework\Http\Controller\TeacherController;
use Marcelofj\LibraryApp\Services\BookService;
use Marcelofj\LibraryApp\Services\StudentService;
use Marcelofj\LibraryApp\Services\TeacherService;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\BookRepositorySQLite;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\StudentRepositorySQLite;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\TeacherRepositorySQLite;
use Marcelofj\LibraryApp\Services\BookLoanService;
use Marcelofj\LibraryApp\Application\BookLoanApplication;

/**
 * Setup the container and configure it before each test.
 *
 * @beforeEach
 */
beforeEach(function () {
    $this->container = new Container();
    ContainerConfig::configure($this->container);
});

/**
 * Test that the BookController can be resolved from the container.
 *
 * @test
 * @return void
 */
it('should resolve BookController from container', function () {
    $bookController = $this->container->get(BookController::class);

    expect($bookController)->toBeInstanceOf(BookController::class);
    expect($bookController)->not->toBeNull();
});

/**
 * Test that the StudentController can be resolved from the container.
 *
 * @test
 * @return void
 */
it('should resolve StudentController from container', function () {
    $studentController = $this->container->get(StudentController::class);

    expect($studentController)->toBeInstanceOf(StudentController::class);
    expect($studentController)->not->toBeNull();
});

/**
 * Test that the TeacherController can be resolved from the container.
 *
 * @test
 * @return void
 */
it('should resolve TeacherController from container', function () {
    $teacherController = $this->container->get(TeacherController::class);

    expect($teacherController)->toBeInstanceOf(TeacherController::class);
    expect($teacherController)->not->toBeNull();
});

/**
 * Test that the BookService can be resolved from the container.
 *
 * @test
 * @return void
 */
it('should resolve BookService from container', function () {
    $bookService = $this->container->get(BookService::class);

    expect($bookService)->toBeInstanceOf(BookService::class);
    expect($bookService)->not->toBeNull();
});

/**
 * Test that the StudentService can be resolved from the container.
 *
 * @test
 * @return void
 */
it('should resolve StudentService from container', function () {
    $studentService = $this->container->get(StudentService::class);

    expect($studentService)->toBeInstanceOf(StudentService::class);
    expect($studentService)->not->toBeNull();
});

/**
 * Test that the TeacherService can be resolved from the container.
 *
 * @test
 * @return void
 */
it('should resolve TeacherService from container', function () {
    $teacherService = $this->container->get(TeacherService::class);

    expect($teacherService)->toBeInstanceOf(TeacherService::class);
    expect($teacherService)->not->toBeNull();
});

/**
 * Test that the BookLoanService can be resolved from the container.
 *
 * @test
 * @return void
 */
it('should resolve BookLoanService from container', function () {
    $bookLoanService = $this->container->get(BookLoanService::class);

    expect($bookLoanService)->toBeInstanceOf(BookLoanService::class);
    expect($bookLoanService)->not->toBeNull();
});

/**
 * Test that the BookRepositorySQLite can be resolved from the container.
 *
 * @test
 * @return void
 */
it('should resolve BookRepositorySQLite from container', function () {
    $bookRepository = $this->container->get(BookRepositorySQLite::class);

    expect($bookRepository)->toBeInstanceOf(BookRepositorySQLite::class);
});

/**
 * Test that the StudentRepositorySQLite can be resolved from the container.
 *
 * @test
 * @return void
 */
it('should resolve StudentRepositorySQLite from container', function () {
    $studentRepository = $this->container->get(StudentRepositorySQLite::class);

    expect($studentRepository)->toBeInstanceOf(StudentRepositorySQLite::class);
});

/**
 * Test that the TeacherRepositorySQLite can be resolved from the container.
 *
 * @test
 * @return void
 */
it('should resolve TeacherRepositorySQLite from container', function () {
    $teacherRepository = $this->container->get(TeacherRepositorySQLite::class);

    expect($teacherRepository)->toBeInstanceOf(TeacherRepositorySQLite::class);
});
