<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeImmutable;
use NatecSdk\Querying\QueryableTrait;
use NatecSdk\Resources\Types\AssortmentUpdateType;

class AssortmentUpdate extends AbstractResource
{
    use QueryableTrait;

    final public function __construct(
        public readonly int $id,
        public readonly string $productNumber,
        public readonly string $productDescription,
        public readonly ?string $newValue,
        public readonly ?string $oldValue,
        public readonly AssortmentUpdateType $type,
        public readonly DateTimeImmutable $updatedAt,
    ) {
    }

    public static function endpoint(): string
    {
        return '/assortment-updates';
    }
}
