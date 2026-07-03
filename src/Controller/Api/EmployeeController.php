<?php

namespace App\Controller\Api;

use App\Exception\NotFoundException;
use App\Service\EmployeeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/employees', name: 'api_employees_')]
final class EmployeeController extends AbstractController
{
    public function __construct(
        private readonly EmployeeService $employeeService,
    ) { }

    #[Route('', name: 'index', methods: 'GET')]
    public function index(): JsonResponse {
        return $this->json($this->employeeService->findAll());
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        try {
            return $this->json($this->employeeService->findOne($id));
        } catch (NotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }


    #[Route('/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->employeeService->delete($id);
            return $this->json(null, 204);
        } catch (NotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

}
