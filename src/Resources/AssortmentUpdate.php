<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeInterface;
use NatecSdk\Querying\Queryable;

class AssortmentUpdate extends Resource
{
    use Queryable;

    public int $id;
    public string $productNumber;
    public string $productDescription;
    public string $newValue;
    public ?string $oldValue;
    public string $type;
    public DateTimeInterface $updatedAt;

    /**
     * @codeCoverageIgnore
     */
    public static function endpoint(): string
    {
        return '/assortment-updates';
    }
}
