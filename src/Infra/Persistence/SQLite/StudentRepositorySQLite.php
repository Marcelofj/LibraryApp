<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Infra\Persistence\SQLite;

use Marcelofj\LibraryApp\Domain\ValueObjects\Email;
use Marcelofj\LibraryApp\Domain\Entities\Student;
use Marcelofj\LibraryApp\Domain\Repositories\StudentRepository;
use Marcelofj\LibraryApp\Domain\Entities\Enums\UserRoleEnum;
use PDO;

/**
 * StudentRepositorySQLite class
 * 
 * This class provides a SQLite-based implementation of the StudentRepository interface,
 * handling the persistence of Student entities within the database.
 */
class StudentRepositorySQLite implements StudentRepository
{
    private PDO $pdo;

    /**
     * Constructor for the StudentRepositorySQLite class.
     *
     * Initializes a PDO connection to the SQLite database.
     */
    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Saves a new Student record in the database.
     *
     * This method inserts a new entry into the `users` table and, using the generated ID, 
     * creates a related record in the `students` table for student-specific data.
     *
     * @param Student $student The Student entity to save.
     */
    public function save(Student $student): void
    {
        $query = 'INSERT INTO users (name, email, role) VALUES (:name, :email, :role)';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':name', $student->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $student->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':role', $student->getRole(), PDO::PARAM_STR);
        $stmt->execute();

        $userId = (int) $this->pdo->lastInsertId();
        $student->setId($userId);

        $query = 'INSERT INTO students (user_id, grade_level, course) VALUES (:user_id, :grade_level, :course)';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':grade_level', $student->getGradeLevel(), PDO::PARAM_STR);
        $stmt->bindValue(':course', $student->getCourse(), PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
     * Retrieves all Student records from the database.
     *
     * This method performs a join between the `users` and `students` tables,
     * returning an array of Student entities.
     *
     * @return array An array of Student entities.
     */
    public function findAll(): array
    {
        $query = 'SELECT u.id, u.name, u.email, s.grade_level, s.course 
                  FROM users u
                  JOIN students s ON u.id = s.user_id
                  WHERE u.role = :role';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':role', UserRoleEnum::STUDENT->getValue(), PDO::PARAM_STR);
        $stmt->execute();

        $students = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $email = new Email($row['email']);
            $student = new Student($row['name'], $email, $row['grade_level'], $row['course']);
            $student->setId((int) $row['id']);
            $students[] = $student;
        }

        return $students;
    }

    /**
     * Find a student by ID.
     *
     * @param int $id
     * @return Student|null
     */
    public function findById(int $id): ?Student
    {
        $query = 'SELECT u.id, u.name, u.email, s.grade_level, s.course 
                  FROM users u
                  JOIN students s ON u.id = s.user_id
                  WHERE s.user_id = :id';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            return null;
        }

        $student = new Student(
            $data['name'],
            new Email($data['email']),
            $data['grade_level'],
            $data['course']
        );
        $student->setId($data['id']);
        return $student;
    }

    /**
     * Deletes a Student record by ID.
     *
     * Deletes a student record from the `users` table by ID, with the condition that the
     * role matches "Student". Also deletes any corresponding entry in the `students` table
     * due to cascading foreign key constraints.
     *
     * @param int $id The ID of the Student to delete.
     * 
     * @return bool Returns true if the deletion was successful, false otherwise.
     */
    public function deleteById(int $id): bool
    {
        $query = 'DELETE FROM users WHERE id = :id AND role = :role';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':role', UserRoleEnum::STUDENT->getValue(), PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
