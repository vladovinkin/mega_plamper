<?php
declare(strict_types=1);

namespace App\Common\Database;

final class ConnectionProvider
{
    const DATABASE_CONFIG_NAME = 'db.ini';

    public static function getConnection(): Connection
    {
        static $connection = null;
        if ($connection === null)
        {
            // TODO: Добавить поддержку .env, чтобы упростить запуск примера в Windows
//            $dsn = self::getEnvString('APP_DATABASE_DSN');
//            $user = self::getEnvString('APP_DATABASE_USER');
//            $password = self::getEnvString('APP_DATABASE_PASSWORD');\
            $dsn = 'mysql:host=127.0.0.1;port=3326;dbname=megaplamper';
            $user = 'megaplamper';
            $password = 'LlcFZi8p';
            $connection = new Connection($dsn, $user, $password);
        }
        return $connection;
    }

    private static function getEnvString(string $name): string
    {
        $value = getenv($name);
        if ($value === false)
        {
            throw new \RuntimeException("Environment variable '$name' not set");
        }
        return (string)$value;
    }
}
