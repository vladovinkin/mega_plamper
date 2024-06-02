<?php
declare(strict_types=1);

namespace App\Model\Data;

class CreateMasterParams
{
    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $phone
     */
    public function __construct(
        private readonly string $firstName,
        private readonly string $lastName,
        private readonly string $phone
    )
    {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
