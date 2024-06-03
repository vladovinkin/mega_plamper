<?php
declare(strict_types=1);

namespace App\Controller\Request;

use App\Model\Data\CreateClientParams;
use App\Model\Data\EditClientParams;

class ClientRequestParser
{
    private const MAX_NAME_LENGTH = 100;
    private const MAX_PHONE_LENGTH = 20;

    public static function parseCreateClientParams(array $parameters): CreateClientParams
    {
        return new CreateClientParams(
            self::parseString($parameters, 'first_name', self::MAX_NAME_LENGTH),
            self::parseString($parameters, 'last_name', self::MAX_NAME_LENGTH),
            self::parseString($parameters, 'phone', self::MAX_PHONE_LENGTH),
        );
    }

    public static function parseEditClientParams(array $parameters): EditClientParams
    {
        return new EditClientParams(
            self::parseInteger($parameters, 'id'),
            self::parseString($parameters, 'first_name', self::MAX_NAME_LENGTH),
            self::parseString($parameters, 'last_name', self::MAX_NAME_LENGTH),
            self::parseString($parameters, 'phone', self::MAX_PHONE_LENGTH),
        );
    }

    public static function parseString(array $parameters, string $name, ?int $maxLength = null): string
    {
        $value = $parameters[$name] ?? null;
        if (!is_string($value))
        {
            throw new RequestValidationException([$name => 'Invalid string value']);
        }
        if ($maxLength !== null && mb_strlen($value) > $maxLength)
        {
            throw new RequestValidationException([$name => "String value too long (exceeds $maxLength characters)"]);
        }
        return $value;
    }

    public static function parseInteger(array $parameters, string $name): int
    {
        $value = $parameters[$name] ?? null;
        if (!self::isIntegerValue($value))
        {
            throw new RequestValidationException([$name => 'Invalid integer value']);
        }
        return (int)$value;
    }

    private static function isIntegerValue(mixed $value): bool
    {
        return is_numeric($value) && (is_int($value) || ctype_digit($value));
    }
}