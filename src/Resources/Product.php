<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use NatecSdk\Querying\QueryableTrait;

class Product extends AbstractResource
{
    use QueryableTrait;

    final public function __construct(
        public readonly int $id,
        public readonly string $no,
        public readonly ?string $vendorItemNo,
        public readonly string $description,
        public readonly int $categoryId,
        public readonly ?int $brandId,
        public readonly ?int $subbrandId,
        public readonly string $itemCategoryCode,
        public readonly string $productGroupCode,
        public readonly string $itemDiscGroup,
        public readonly Category $category,
    ) {
    }

    public static function create(array $data, array $propertyValues = []): static
    {
        /** @var array<string, mixed> $categoryData */
        $categoryData = $data['category'];
        $propertyValues['category'] = Category::create($categoryData);
        unset($data['category']);

        return parent::create($data, $propertyValues);
    }

    public static function endpoint(): string
    {
        return '/products';
    }
}
