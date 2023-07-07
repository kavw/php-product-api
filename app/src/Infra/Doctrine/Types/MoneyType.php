<?php

declare(strict_types=1);

namespace App\Infra\Doctrine\Types;

use App\Defaults;
use App\Infra\Doctrine\AppTypes;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BigIntType;
use Doctrine\DBAL\Types\Type;
use Money\Currency;
use Money\Money;

final class MoneyType extends Type
{
    /**
     * @inheritdoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return new Money(0, new Currency(Defaults::CURRENCY));
        }

        if (is_int($value) || is_string($value)) {
            return new Money($value, new Currency(Defaults::CURRENCY));
        }

        throw new \RuntimeException(
            "The value param is expected to be an integer. Given " . gettype($value)
        );
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        /**
         * @var Money $value
         */
        return $value->getAmount();
    }

    public function getName(): string
    {
        return AppTypes::MONEY;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getBigIntTypeDeclarationSQL($column);
    }
}
