<?php
declare(strict_types=1);

namespace App\Model;

use DateTimeImmutable;

class Master
{
    private ?int $id;
    private string $firstName;
    private string $lastName;
    private string $phone;
    private ?DateTimeImmutable $deletedAt;

    /**
     * @param int|null $id
     * @param string $firstName
     * @param string $lastName
     * @param string $phone
     * @param DateTimeImmutable|null $deletedAt
     */
    public function __construct(
        ?int $id,
        string $firstName,
        string $lastName,
        string $phone,
        ?DateTimeImmutable $deletedAt
    )
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->deletedAt = $deletedAt;
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $phone
     * @param DateTimeImmutable|null $deletedAt
     * @return void
     */
    public function edit(string $firstName, string $lastName, string $phone, ?DateTimeImmutable $deletedAt): void
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->deletedAt = $deletedAt;
    }

    public function getId(): ?int
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

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }
}