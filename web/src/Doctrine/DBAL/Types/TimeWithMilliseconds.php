<?php

namespace App\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class TimeWithMilliseconds extends Type
{
    public const TIME_WITH_MILLISECONDS = 'time_with_milliseconds';

    public const DATE_FORMAT = 'H:i:s.v';

    /**
     * @return string
     */
    public function getName()
    {
        return self::TIME_WITH_MILLISECONDS;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'TIME(3)';
    }

    /**
     * @param mixed $value
     *
     * @return bool|\DateTime|mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        return \DateTime::createFromFormat(self::DATE_FORMAT, $value);
    }

    /**
     * @param mixed $value
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return $value;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format(self::DATE_FORMAT);
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'DateTime']);
    }

    /**
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
