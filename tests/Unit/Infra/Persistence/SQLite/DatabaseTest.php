<?php

use Marcelofj\LibraryApp\Infra\Persistence\SQLite\Database;

/**
 * Test that the Database class is a singleton instance.
 *
 * This test verifies that when calling `Database::getInstance()` multiple times, 
 * the same instance of the Database is returned.
 *
 * @test
 * @return void
 */
test('should be a singleton instance', function () {
    $db1 = Database::getInstance();
    $db2 = Database::getInstance();

    expect($db1)->toBe($db2);
});

/**
 * Test that the Database class establishes a connection to the database.
 *
 * This test checks that the `getConnection()` method returns a valid PDO instance, 
 * ensuring that the database connection is correctly established.
 *
 * @test
 * @return void
 */
test('should establish a connection to the database', function () {
    $db = Database::getInstance();

    expect($db->getConnection())->toBeInstanceOf(PDO::class);
});

/**
 * Test that the Database instance cannot be cloned.
 *
 * This test ensures that cloning the Database class instance throws a `LogicException`, 
 * preserving the singleton pattern.
 *
 * @test
 * @return void
 */
test('should not allow cloning of the instance', function () {
    $db = Database::getInstance();

    expect(fn() => clone $db)->toThrow(\LogicException::class);
});

/**
 * Test that the Database instance cannot be unserialized.
 *
 * This test checks that attempting to unserialize the Database instance throws a `LogicException`, 
 * enforcing the singleton pattern.
 *
 * @test
 * @return void
 */
test('should not allow unserializing of the instance', function () {
    $db = Database::getInstance();

    $unserializeFunction = function () {
        $this->__wakeup();
    };

    $unserializeFunction = $unserializeFunction->bindTo($db, Database::class);

    expect($unserializeFunction)->toThrow(\LogicException::class);
});

/**
 * Test that an exception is thrown if there is a connection error.
 *
 * This test simulates a connection error by mocking the `getConnection()` method 
 * to throw a `PDOException`, ensuring that the application handles database connection failures gracefully.
 *
 * @test
 * @return void
 */
test('should throw an exception for connection error', function () {
    $mockDb = $this->getMockBuilder(Database::class)
        ->disableOriginalConstructor()
        ->onlyMethods(['getConnection'])
        ->getMock();

    $mockDb->method('getConnection')->will($this->throwException(new \PDOException("Simulated connection error")));

    expect(fn() => $mockDb->getConnection())->toThrow(\PDOException::class);
});
