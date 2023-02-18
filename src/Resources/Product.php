<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use NatecSdk\Querying\Queryable;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class Product extends Resource
{
    use Queryable;

    /**
     * @param array<\NatecSdk\Resources\Price> $prices
     */
    final public function __construct(
        public readonly string $productNumber,
        public readonly string $category,
        public readonly int $itemCategoryCode,
        public readonly string $brand,
        public readonly ?string $subBrand,
        public readonly string $description,
        public readonly ?string $extraDescription,
        public readonly string $productGroupCode,
        public readonly string $itemDiscGroup,
        public readonly int $wattpiek,
        public readonly ?string $cellType,
        public readonly ?string $oType,
        public readonly ?int $numberOfCells,
        public readonly ?int $voltage,
        public readonly ?int $fase,
        public readonly ?int $length,
        public readonly ?int $width,
        public readonly ?int $height,
        public readonly string $stockInformation,
        public readonly ?string $articleNumberFabric,
        public readonly ?string $image,
        public readonly ?string $datasheet,
        public readonly string $extraDeliveryTimeInfo,
        public readonly bool $expiringItem,
        public readonly array $prices,
    ) {
    }

    public static function create(array $data, array $propertyValues = []): static
    {
        $propertyValues['prices'] = [];

        /** @var array<array<string, mixed>> $prices */
        $prices = $data['prices'];

        foreach ($prices as $price) {
            $propertyValues['prices'] = Price::create($price);
        }

        unset($data['prices']);

        return parent::create($data, $propertyValues);
    }

    #[CodeCoverageIgnore]
    public static function endpoint(): string
    {
        return '/products';
    }
}
