<?php
declare(strict_types=1);

namespace App\Database;

use App\Common\Database\Connection;
use App\Common\Database\DatabaseDateFormat;
use App\Model\Master;

class MasterRepository
{
    public function __construct(
        private readonly Connection $connection
    )
    {
    }

    /**
     * @return Master[]
     */
    public function list(): array
    {
        $query = <<<SQL
            SELECT
              id,
              first_name,
              last_name,
              phone
            FROM master
            WHERE deleted_at IS NULL
            ORDER BY last_name
            SQL;
        $stmt = $this->connection->execute($query);

        return array_map(
            fn($row) => $this->hydrateMaster($row),
            $stmt->fetchAll(\PDO::FETCH_ASSOC)
        );
    }

    /**
     * @param int $id
     * @return Master|null
     */
    public function findOne(int $id): ?Master
    {
        $query = <<<SQL
            SELECT
              id,
              first_name,
              last_name,
              phone
            FROM master
            WHERE deleted_at IS NULL
            AND id = ?
            SQL;

        $params = [$id];
        $stmt = $this->connection->execute($query, $params);
        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
        {
            return $this->hydrateMaster($row);
        }
        return null;
    }

    /**
     * @param Master $master
     * @return int
     */
    public function save(Master $master): int
    {
        $masterId = $master->getId();
        if ($masterId)
        {
            $this->updateMaster($master);
        }
        else
        {
            $masterId = $this->insertMaster($master);
        }

        return $masterId;
    }

    /**
     * @param int[] $ids
     * @return void
     */
    public function delete(array $ids): void
    {
        if (count($ids) === 0)
        {
            return;
        }

        $dateTime = date(DatabaseDateFormat::MYSQL_DATETIME_FORMAT);
        $placeholders = substr(str_repeat('?,', count($ids)), 0, -1);
        $this->connection->execute(
            <<<SQL
            UPDATE master 
            SET deleted_at = '$dateTime' 
            WHERE id IN ($placeholders)
            SQL,
            $ids
        );
    }

    /**
     * @param Master $master
     * @return int
     */
    private function insertMaster(Master $master): int
    {
        $query = <<<SQL
            INSERT INTO master
              (first_name, last_name, phone)
            VALUES
              (:first_name, :last_name, :phone)
            SQL;
        $params = [
            ':first_name' => $master->getFirstName(),
            ':last_name' => $master->getLastName(),
            ':phone' => $master->getPhone(),
        ];

        $this->connection->execute($query, $params);

        return $this->connection->getLastInsertId();
    }

    /**
     * @param Master $master
     * @return void
     */
    private function updateMaster(Master $master): void
    {
        $query = <<<SQL
            UPDATE master
            SET
              id = :id,
              first_name = :first_name,
              last_name = :last_name
            WHERE id = :id
            SQL;
        $params = [
            ':id' => $master->getId(),
            ':first_name' => $master->getFirstName(),
            ':last_name' => $master->getLastName(),
        ];

        $this->connection->execute($query, $params);
    }

    /**
     * @param array $row
     * @return Master
     */
    private function hydrateMaster(array $row): Master
    {
        try
        {
            return new Master(
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
