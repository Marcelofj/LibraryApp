<?php

namespace Marcelofj\LibraryApp\Infra\Framework\Http\Controller;

use Marcelofj\LibraryApp\Services\TeacherService;
use Marcelofj\LibraryApp\Domain\Entities\Teacher;
use Marcelofj\LibraryApp\Domain\ValueObjects\Email;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * TeacherController class
 *
 * This controller handles HTTP requests related to the teacher entity.
 * It defines the logic for adding, retrieving, and deleting teachers.
 */
class TeacherController
{
    /**
     * Constructor for the TeacherController.
     *
     * @param TeacherService $teacherService The service responsible for handling the teacher-related operations.
     */
    public function __construct(private TeacherService $teacherService) {}

    /**
     * Handles the request to add a new teacher.
     *
     * This method receives the request data (JSON), creates a Teacher object, 
     * and delegates the addition of the teacher to the TeacherService.
     * 
     * @param ServerRequestInterface $request The HTTP request object.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response with the result of the operation.
     */
    public function addTeacher(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);
        $email = new Email($data['email']);
        $teacher = new Teacher($data['name'], $email, $data['department']);
        $this->teacherService->addTeacher($teacher);
        $response->getBody()->write(json_encode(['status' => 'Teacher added successfully']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Handles the request to retrieve all teachers.
     *
     * This method retrieves all teachers from the TeacherService, formats the data,
     * and returns it as a JSON response.
     * 
     * @param ServerRequestInterface $request The HTTP request object.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response containing a JSON list of teachers.
     */
    public function getAllTeachers(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $teachers = $this->teacherService->listTeachers();
        $teachersData = array_map(function (Teacher $teacher) {
            return [
                'id' => $teacher->getId(),
                'name' => $teacher->getName(),
                'email' => $teacher->getEmail(),
                'department' => $teacher->getDepartment(),
                'role' => $teacher->getRole(),
            ];
        }, $teachers);
        $response->getBody()->write(json_encode($teachersData));
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Retrieves a teacher by their ID.
     *
     * This method attempts to retrieve a teacher based on the provided ID.
     * If the teacher is found, their data is returned in the response.
     * If the teacher is not found, an error message is returned.
     * 
     * @param ServerRequestInterface $request The HTTP request object containing the teacher ID in the URL.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response with the teacher data or an error message.
     */
    public function getTeacherById(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $teacher = $this->teacherService->getTeacherById($id);

        if ($teacher) {
            $response->getBody()->write(json_encode([
                'id' => $teacher->getId(),
                'name' => $teacher->getName(),
                'email' => $teacher->getEmail(),
                'department' => $teacher->getDepartment(),
                'role' => $teacher->getRole(),
            ]));
        } else {
            $response->getBody()->write(json_encode(['error' => 'Teacher not found']));
        }

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    /**
     * Handles the request to delete a teacher by their ID.
     *
     * This method attempts to delete a teacher using their ID, which is provided
     * in the URL. If the deletion is successful, a success message is returned.
     * Otherwise, a not found message is returned.
     * 
     * @param ServerRequestInterface $request The HTTP request object.
     * @param ResponseInterface $response The HTTP response object.
     * 
     * @return ResponseInterface The HTTP response with a status indicating success or failure.
     */
    public function deleteTeacher(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $id = (int) $request->getAttribute('id');
        $isDeleted = $this->teacherService->deleteTeacher($id);

        if ($isDeleted) {
            $response->getBody()->write(json_encode(['status' => 'Teacher deleted successfully']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }

        $response->getBody()->write(json_encode(['status' => 'Teacher not found']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
}
