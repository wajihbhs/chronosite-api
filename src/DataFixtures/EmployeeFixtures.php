<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EmployeeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $employees = [
          ['firstName' => 'Wajih', 'lastName' => "BenelHaj Sghaier", 'badgeNumber' => 'A1234'],
          ['firstName' => 'Hiba', 'lastName' => "Chabani ep Sghaier", 'badgeNumber' => 'B1234'],
          ['firstName' => 'Vincent', 'lastName' => "Kaymer", 'badgeNumber' => 'C1234'],
          ['firstName' => 'Levon', 'lastName' => "Aronien", 'badgeNumber' => 'D1234'],
        ];
        foreach ($employees as $data) {
            $employee = (new Employee())
                ->setFirstName($data['firstName'])
                ->setLastName($data['lastName'])
                ->setBadgeNumber($data['badgeNumber'])
                ;

            $manager->persist($employee);
            $this->addReference('employee-' . $data['badgeNumber'], $employee);
        }

        $manager->flush();
    }
}
