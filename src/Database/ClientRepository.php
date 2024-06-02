<?php
declare(strict_types=1);

namespace App\Database;

use App\Common\Database\Connection;
use App\Model\Client;

class ClientRepository
{
    public function __construct(
        private readonly Connection $connection
    )
    {
    }

    /**
     * @return Client[]
     */
    public function list(): array
    {
        $query = <<<SQL
            SELECT
              id,
              first_name,
              last_name,
              phone
            FROM client
            ORDER BY last_name
            SQL;
        $stmt = $this->connection->execute($query);

        return array_map(
            fn($row) => $this->hydrateClient($row),
            $stmt->fetchAll(\PDO::FETCH_ASSOC)
        );
    }

    /**
     * @param int $id
     * @return Client|null
     */
    public function findOne(int $id): ?Client
    {
        $query = <<<SQL
            SELECT
              id,
              first_name,
              last_name,
              phone
            FROM client
            WHERE id = ?
            SQL;

        $params = [$id];
        $stmt = $this->connection->execute($query, $params);
        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
        {
            return $this->hydrateClient($row);
        }
        return null;
    }

    /**
     * @param Client $client
     * @return int
     */
    public function save(Client $client): int
    {
        $clientId = $client->getId();
        if ($clientId)
        {
            $this->updateClient($client);
        }
        else
        {
            $clientId = $this->insertClient($client);
        }

        return $clientId;
    }

    /**
     * @param Client $client
     * @return int
     */
    private function insertClient(Client $client): int
    {
        $query = <<<SQL
            INSERT INTO client
              (first_name, last_name, phone)
            VALUES
              (:first_name, :last_name, :phone)
            SQL;
        $params = [
            ':first_name' => $client->getFirstName(),
            ':last_name' => $client->getLastName(),
            ':phone' => $client->getPhone(),
        ];

        $this->connection->execute($query, $params);

        return $this->connection->getLastInsertId();
    }

    /**
     * @param Client $client
     * @return void
     */
    private function updateClient(Client $client): void
    {
        $query = <<<SQL
            UPDATE client
            SET
              id = :id,
              first_name = :first_name,
              last_name = :last_name
            WHERE id = :id
            SQL;
        $params = [
            ':id' => $client->getId(),
            ':first_name' => $client->getFirstName(),
            ':last_name' => $client->getLastName(),
        ];

        $this->connection->execute($query, $params);
    }

    /**
     * @param array $row
     * @return Client
     */
    private function hydrateClient(array $row): Client
    {
        try
        {
            return new Client(
                (int)$row['id'],
                (string)$row['first_name'],
                (string)$row['last_name'],
                (string)$row['phone']
            );
        }
        catch (\Exception $e)
        {
            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
