<?php

namespace App\Service;

use App\Entity\Employee;
use App\Exception\NotFoundException;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;

final class EmployeeService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EmployeeRepository $employeeRepository,
    ) {}

    public function findAll(): array {
        return $this->employeeRepository->findAll();
    }

    public function findOne($id): Employee {
        return $this->employeeRepository->find($id)
                ?? throw new NotFoundException('employee.not_found', ['id' => $id])
        ;
    }

    public function delete($id): void {
        $this->em->remove($this->findOne($id));
        $this->em->flush();
    }
}
