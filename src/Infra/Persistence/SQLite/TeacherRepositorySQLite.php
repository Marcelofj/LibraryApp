<?php

declare(strict_types=1);

namespace Marcelofj\LibraryApp\Infra\Persistence\SQLite;

use Marcelofj\LibraryApp\Domain\ValueObjects\Email;
use Marcelofj\LibraryApp\Domain\Entities\Teacher;
use Marcelofj\LibraryApp\Domain\Repositories\TeacherRepository;
use Marcelofj\LibraryApp\Domain\Entities\Enums\UserRoleEnum;
use PDO;

/**
 * TeacherRepositorySQLite class
 * 
 * This class provides a SQLite-based implementation of the TeacherRepository interface,
 * handling the persistence of Teacher entities within the database.
 */
class TeacherRepositorySQLite implements TeacherRepository
{
    private PDO $pdo;

    /**
     * Constructor for the TeacherRepositorySQLite class.
     *
     * Initializes a PDO connection to the SQLite database.
     */
    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Saves a new Teacher record in the database.
     *
     * This method inserts a new entry into the `users` table and, using the generated ID, 
     * creates a related record in the `Teachers` table for Teacher-specific data.
     *
     * @param Teacher $teacher The Teacher entity to save.
     */
    public function save(Teacher $teacher): void
    {
        $query = 'INSERT INTO users (name, email, role) VALUES (:name, :email, :role)';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':name', $teacher->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $teacher->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':role', $teacher->getRole(), PDO::PARAM_STR);
        $stmt->execute();

        $userId = (int) $this->pdo->lastInsertId();
        $teacher->setId($userId);

        $query = 'INSERT INTO teachers (user_id, department) VALUES (:user_id, :department)';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':department', $teacher->getDepartment(), PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
     * Retrieves all Teacher records from the database.
     *
     * This method performs a join between the `users` and `Teachers` tables,
     * returning an array of Teacher entities.
     *
     * @return array An array of Teacher entities.
     */
    public function findAll(): array
    {
        $query = 'SELECT u.id, u.name, u.email, t.department 
                  FROM users u
                  JOIN teachers t ON u.id = t.user_id
                  WHERE u.role = :role';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':role', UserRoleEnum::TEACHER->getValue(), PDO::PARAM_STR);
        $stmt->execute();

        $teachers = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $email = new Email($row['email']);
            $teacher = new Teacher($row['name'], $email, $row['department']);
            $teacher->setId((int) $row['id']);
            $teachers[] = $teacher;
        }

        return $teachers;
    }

    /**
     * Find a teacher by ID.
     *
     * @param int $id
     * @return Teacher|null
     */
    public function findById(int $id): ?Teacher
    {
        $query = 'SELECT u.id, u.name, u.email, t.department 
                  FROM users u
                  JOIN teachers t ON u.id = t.user_id
                  WHERE t.user_id = :id';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            return null;
        }

        $teacher = new Teacher(
            $data['name'],
            new Email($data['email']),
            $data['department'],
        );
        $teacher->setId($data['id']);
        return $teacher;
    }

    /**
     * Deletes a Teacher record by ID.
     *
     * Deletes a Teacher record from the `users` table by ID, with the condition that the
     * role matches "Teacher". Also deletes any corresponding entry in the `Teachers` table
     * due to cascading foreign key constraints.
     *
     * @param int $id The ID of the Teacher to delete.
     * 
     * @return bool Returns true if the deletion was successful, false otherwise.
     */
    public function deleteById(int $id): bool
    {
        $query = 'DELETE FROM users WHERE id = :id AND role = :role';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':role', UserRoleEnum::TEACHER->getValue(), PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
