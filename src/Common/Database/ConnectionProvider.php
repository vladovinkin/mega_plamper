<?php
declare(strict_types=1);

namespace App\Common\Database;

use Dotenv\Dotenv;

final class ConnectionProvider
{
    public static function getConnection(): Connection
    {
        static $connection = null;
        if ($connection === null)
        {
            $dotenv = Dotenv::createImmutable(__DIR__.'/../../../');
            $dotenv->safeLoad();
            $dotenv->required(['APP_DATABASE_DSN', 'APP_DATABASE_USER', 'APP_DATABASE_PASSWORD']);

            $dsn = self::getEnvString('APP_DATABASE_DSN');
            $user = self::getEnvString('APP_DATABASE_USER');
            $password = self::getEnvString('APP_DATABASE_PASSWORD');

            $connection = new Connection($dsn, $user, $password);
        }
        return $connection;
    }

    private static function getEnvString(string $name): string
    {
        $value = $_ENV[$name];
        if ($value === false)
        {
            throw new \RuntimeException("Environment variable '$name' not set");
        }
        return (string)$value;
    }
}
