<?php
declare(strict_types=1);

namespace App\Database;

use App\Common\Database\Connection;
use App\Common\Database\DatabaseDateFormat;
use App\Model\Master;
//use App\Model\Exception\OptimisticLockException;

class MasterRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Master[]
     */
    public function listActive(): array
    {
        $query = <<<SQL
            SELECT
              m.id,
              m.first_name,
              m.last_name,
              m.phone,
              m.deleted_at
            FROM master m
            WHERE m.deleted_at IS NULL
            ORDER BY m.last_name
            SQL;
        $stmt = $this->connection->execute($query);

        return array_map(
            fn($row) => $this->hydrateMaster($row),
            $stmt->fetchAll(\PDO::FETCH_ASSOC)
        );
    }

    public function findOne(int $id): ?Master
    {
        $query = <<<SQL
            SELECT
              m.id,
              m.first_name,
              m.last_name,
              m.phone,
              m.deleted_at
            FROM master m
            WHERE m.id = ?
            SQL;

        $params = [$id];
        $stmt = $this->connection->execute($query, $params);
        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
        {
            return $this->hydrateMaster($row);
        }
        return null;
    }

//    public function save(Article $article): int
//    {
//        $articleId = $article->getId();
//        if ($articleId)
//        {
//            $this->updateArticle($article);
//        }
//        else
//        {
//            $articleId = $this->insertArticle($article);
//        }
//
//        $this->saveArticleTags($articleId, $article->getTags());
//
//        return $articleId;
//    }

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

        $placeholders = substr(str_repeat('?,', count($ids)), 0, -1);
        $this->connection->execute(
            <<<SQL
            DELETE FROM master WHERE id IN ($placeholders)
            SQL,
            $ids
        );
    }

    private function hydrateMaster(array $row): Master
    {
        try
        {
            return new Master(
                (int)$row['id'],
                (string)$row['first_name'],
                (string)$row['last_name'],
                (string)$row['phone'],
                $this->parseDateTimeOrNull($row['deleted_at']),
            );
        }
        catch (\Exception $e)
        {
            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

//    private function insertArticle(Article $article): int
//    {
//        $query = <<<SQL
//            INSERT INTO article
//              (version, title, content, created_at, created_by, updated_at, updated_by)
//            VALUES
//              (:version, :title, :content, :created_at, :created_by, :updated_at, :updated_by)
//            SQL;
//        $params = [
//            ':version' => $article->getVersion(),
//            ':title' => $article->getTitle(),
//            ':content' => $article->getContent(),
//            ':created_at' => $this->formatDateTimeOrNull($article->getCreatedAt()),
//            ':created_by' => $article->getCreatedBy(),
//            ':updated_at' => $this->formatDateTimeOrNull($article->getUpdatedAt()),
//            ':updated_by' => $article->getUpdatedBy()
//        ];
//
//        $this->connection->execute($query, $params);
//
//        return $this->connection->getLastInsertId();
//    }
//
//    private function updateArticle(Article $article): void
//    {
//        // NOTE: Оптимистичная блокировка реализована за счёт
//        //  1. Условия "version = :version" в WHERE
//        //  2. Проверки числа изменённых колонок
//        $query = <<<SQL
//            UPDATE article
//            SET
//              id = :id,
//              version = version + 1,
//              title = :title,
//              content = :content,
//              created_at = :created_at,
//              created_by = :created_by,
//              updated_at = :updated_at,
//              updated_by = :updated_by
//            WHERE id = :id
//              AND version = :version
//            SQL;
//        $params = [
//            ':id' => $article->getId(),
//            ':version' => $article->getVersion(),
//            ':title' => $article->getTitle(),
//            ':content' => $article->getContent(),
//            ':created_at' => $this->formatDateTimeOrNull($article->getCreatedAt()),
//            ':created_by' => $article->getCreatedBy(),
//            ':updated_at' => $this->formatDateTimeOrNull($article->getUpdatedAt()),
//            ':updated_by' => $article->getUpdatedBy()
//        ];
//
//        $stmt = $this->connection->execute($query, $params);
//        if (!$stmt->rowCount())
//        {
//            throw new OptimisticLockException("Optimistic lock failed for article {$article->getId()}");
//        }
//    }

//    private function formatDateTimeOrNull(?\DateTimeImmutable $dateTime): ?string
//    {
//        return $dateTime?->format(DatabaseDateFormat::MYSQL_DATETIME_FORMAT);
//    }
//
    private function parseDateTimeOrNull(?string $value): ?\DateTimeImmutable
    {
        try
        {
            return ($value !== null) ? new \DateTimeImmutable($value, new \DateTimeZone('Etc/UTC')) : null;
        }
        catch (\Exception $e)
        {
            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
