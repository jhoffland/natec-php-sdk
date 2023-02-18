<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeImmutable;
use NatecSdk\Querying\Queryable;
use NatecSdk\Resources\Types\AssortmentUpdateType;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class AssortmentUpdate extends Resource
{
    use Queryable;

    final public function __construct(
        public readonly int $id,
        public readonly string $productNumber,
        public readonly string $productDescription,
        public readonly string $newValue,
        public readonly ?string $oldValue,
        public readonly AssortmentUpdateType $type,
        public readonly DateTimeImmutable $updatedAt,
    ) {
    }

    #[CodeCoverageIgnore]
    public static function endpoint(): string
    {
        return '/assortment-updates';
    }
}
