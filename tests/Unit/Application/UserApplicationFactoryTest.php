<?php

use Marcelofj\LibraryApp\Application\UserApplication;
use Marcelofj\LibraryApp\Application\UserApplicationFactory;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\TeacherRepositorySQLite;
use Marcelofj\LibraryApp\Infra\Persistence\SQLite\StudentRepositorySQLite;

/**
 * Test case for creating a UserApplication instance.
 * 
 * This test verifies that the UserApplicationFactory correctly creates a 
 * UserApplication instance when provided with mock instances of 
 * TeacherRepositorySQLite and StudentRepositorySQLite.
 * It checks that the returned object is an instance of the UserApplication class.
 *
 * @test
 * @return void
 */
test('should create a UserApplication instance with the provided repositories', function () {
    $teacherRepo = \Mockery::mock(TeacherRepositorySQLite::class);
    $studentRepo = \Mockery::mock(StudentRepositorySQLite::class);

    $userApplication = UserApplicationFactory::create($teacherRepo, $studentRepo);

    expect($userApplication)->toBeInstanceOf(UserApplication::class);
});
