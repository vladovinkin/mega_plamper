<?php
declare(strict_types=1);

namespace App\Model\Data;

class EditClientParams
{
    /**
     * @param int $id
     * @param string $firstName
     * @param string $lastName
     * @param string $phone
     */
    public function __construct(
        private readonly int $id,
        private readonly string $firstName,
        private readonly string $lastName,
        private readonly string $phone
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
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
