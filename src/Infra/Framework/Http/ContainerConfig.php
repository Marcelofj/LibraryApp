<?php

namespace Marcelofj\LibraryApp\Infra\Framework\Http;

use DI\Container;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\BookRepositorySQLite;
use Marcelofj\LibraryApp\Services\BookService;
use Marcelofj\LibraryApp\Infra\Framework\Http\Controller\BookController;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\StudentRepositorySQLite;
use Marcelofj\LibraryApp\Services\StudentService;
use Marcelofj\LibraryApp\Infra\Framework\Http\Controller\StudentController;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\TeacherRepositorySQLite;
use Marcelofj\LibraryApp\Services\TeacherService;
use Marcelofj\LibraryApp\Infra\Framework\Http\Controller\TeacherController;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\BookLoanRepositorySQLite;
use Marcelofj\LibraryApp\Services\BookLoanService;
use Marcelofj\LibraryApp\Infra\Framework\Http\Controller\BookLoanController;

/**
 * ContainerConfig class
 *
 * This class configures the dependency injection container. It defines how various
 * services, repositories, and controllers should be instantiated and injected 
 * into each other. It uses PHP-DI (a dependency injection container) to manage the 
 * dependencies of the application.
 */
class ContainerConfig
{
    /**
     * Configures the dependency injection container.
     *
     * This method registers various classes (repositories, services, and controllers) 
     * in the container so they can be resolved with automatic dependency injection.
     * 
     * @param Container $container The dependency injection container.
     */
    public static function configure(Container $container): void
    {
        // Repositories
        $container->set(BookRepositorySQLite::class, function () {
            return new BookRepositorySQLite();
        });

        $container->set(StudentRepositorySQLite::class, function () {
            return new StudentRepositorySQLite();
        });

        $container->set(TeacherRepositorySQLite::class, function () {
            return new TeacherRepositorySQLite();
        });

        $container->set(BookLoanRepositorySQLite::class, function () {
            return new BookLoanRepositorySQLite();
        });

        // Services
        $container->set(BookService::class, function ($container) {
            return new BookService($container->get(BookRepositorySQLite::class));
        });

        $container->set(StudentService::class, function ($container) {
            return new StudentService($container->get(StudentRepositorySQLite::class));
        });

        $container->set(TeacherService::class, function ($container) {
            return new TeacherService($container->get(TeacherRepositorySQLite::class));
        });

        $container->set(BookLoanService::class, function ($container) {
            return new BookLoanService($container->get(BookLoanRepositorySQLite::class));
        });

        // Controllers
        $container->set(BookController::class, function ($container) {
            return new BookController($container->get(BookService::class));
        });

        $container->set(StudentController::class, function ($container) {
            return new StudentController($container->get(StudentService::class));
        });

        $container->set(TeacherController::class, function ($container) {
            return new TeacherController($container->get(TeacherService::class));
        });

        $container->set(BookLoanController::class, function ($container) {
            return new BookLoanController($container->get(BookLoanService::class));
        });
    }
}
