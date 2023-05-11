<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use DateTimeImmutable;

class Category extends AbstractResource
{
    final public function __construct(
        public readonly int $id,
        public readonly ?int $rootId,
        public readonly ?int $parentId,
        public readonly string $code,
        public readonly string $description,
        public readonly ?int $sortOrder,
        public readonly DateTimeImmutable $createdAt,
        public readonly DateTimeImmutable $updatedAt,
        public readonly ?Category $root,
    ) {
    }

    public static function create(array $data, array $propertyValues = []): static
    {
        if (isset($data['root'])) {
            /** @var array<string, mixed> $rootData */
            $rootData = $data['root'];
            $propertyValues['root'] = Category::create($rootData);
            unset($data['root']);
        } else {
            $propertyValues['root'] = null;
        }

        return parent::create($data, $propertyValues);
    }
}
