<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[ORM\Table(name: 'employees')]
#[UniqueEntity(fields: ['badgeNumber'], message: 'employee.badge_number.duplicate')]

class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'employee.first_name.required')]
    #[Assert\Length(min: 2, max: 100, minMessage: 'employee.first_name.length')]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'employee.last_name.required')]
    #[Assert\Length(min: 2, max: 100, minMessage: 'employee.last_name.length')]
    private ?string $lastName = null;

    #[ORM\Column(length: 50, unique: true)]
    #[Assert\NotBlank(message: 'employee.badge_number.required')]
    #[Assert\Regex(
        pattern: '/^[A-Z0-9\-]+$/',
        message: 'employee.badge_number.format',
    )]
    private ?string $badgeNumber = null;

    public function getId(): ?int { return $this->id; }

    public function getFirstName(): ?string { return $this->firstName; }
    public function setFirstName(string $v): static { $this->firstName = $v; return $this; }

    public function getLastName(): ?string { return $this->lastName; }
    public function setLastName(string $v): static { $this->lastName = $v; return $this; }

    public function getBadgeNumber(): ?string { return $this->badgeNumber; }
    public function setBadgeNumber(string $v): static
    {
        $this->badgeNumber = strtoupper($v);

        return $this;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
