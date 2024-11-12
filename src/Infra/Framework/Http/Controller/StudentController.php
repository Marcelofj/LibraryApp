<?php

namespace Marcelofj\LibraryApp\Infra\Framework\Http\Controller;

use Marcelofj\LibraryApp\Services\StudentService;
use Marcelofj\LibraryApp\Domain\Entities\Student;
use Marcelofj\LibraryApp\Domain\ValueObjects\Email;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * StudentController class
 *
 * This controller handles HTTP requests related to the student entity.
 * It defines the logic for adding, retrieving, and deleting students.
 */
class StudentController
{
    /**
     * Constructor for the StudentController.
     *
     * @param StudentService $studentService The service responsible for handling the student-related operations.
     */
    public function __construct(private StudentService $studentService) {}

    /**
     * Handles the request to add a new student.
     *
     * This method receives the request data (JSON), creates a Student object, 
     * and delegates the addition of the student to the StudentService.
     * 
     * @param ServerRequestInterface $request The HTTP request object.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response with the result of the operation.
     */
    public function addStudent(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);
        $email = new Email($data['email']);
        $student = new Student($data['name'], $email, $data['grade_level'], $data['course']);
        $this->studentService->addStudent($student);
        $response->getBody()->write(json_encode(['status' => 'Student added successfully']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Handles the request to retrieve all students.
     *
     * This method retrieves all students from the StudentService, formats the data,
     * and returns it as a JSON response.
     * 
     * @param ServerRequestInterface $request The HTTP request object.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response containing a JSON list of students.
     */
    public function getAllStudents(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $students = $this->studentService->listStudents();
        $studentsData = array_map(function (Student $student) {
            return [
                'id' => $student->getId(),
                'name' => $student->getName(),
                'email' => $student->getEmail(),
                'grade_level' => $student->getGradeLevel(),
                'course' => $student->getCourse(),
                'role' => $student->getRole(),
            ];
        }, $students);
        $response->getBody()->write(json_encode($studentsData));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Retrieves a student by their ID.
     *
     * This method attempts to retrieve a student based on the provided ID.
     * If the student is found, their data is returned in the response.
     * If the student is not found, an error message is returned.
     * 
     * @param ServerRequestInterface $request The HTTP request object containing the student ID in the URL.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response with the student data or an error message.
     */
    public function getStudentById(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $student = $this->studentService->getStudentById($id);

        if ($student) {
            $response->getBody()->write(json_encode([
                'id' => $student->getId(),
                'name' => $student->getName(),
                'email' => $student->getEmail(),
                'grade_level' => $student->getGradeLevel(),
                'course' => $student->getCourse(),
                'role' => $student->getRole(),
            ]));
        } else {
            $response->getBody()->write(json_encode(['error' => 'Student not found']));
        }

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    /**
     * Handles the request to delete a student by their ID.
     *
     * This method attempts to delete a student using their ID, which is provided
     * in the URL. If the deletion is successful, a success message is returned.
     * Otherwise, a not found message is returned.
     * 
     * @param ServerRequestInterface $request The HTTP request object.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response with a status indicating success or failure.
     */
    public function deleteStudent(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $isDeleted = $this->studentService->deleteStudent($id);

        if ($isDeleted) {
            $response->getBody()->write(json_encode(['status' => 'Student deleted successfully']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }

        $response->getBody()->write(json_encode(['status' => 'Student not found']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
}
