<?php declare(strict_types=1);

namespace NatecSdk\Resources;

use NatecSdk\Exceptions\NatecSdkException;
use NatecSdk\Querying\Queryable;

class Product extends Resource
{
    use Queryable;

    public string $productNumber;
    public string $category;
    public int $itemCategoryCode;
    public string $brand;
    public ?string $subBrand;
    public string $description;
    public ?string $extraDescription;
    public string $productGroupCode;
    public string $itemDiscGroup;
    public int $wattpiek;
    public ?string $cellType;
    public ?string $oType;
    public ?int $numberOfCells;
    public ?int $voltage;
    public ?int $fase;
    public ?int $length;
    public ?int $width;
    public ?int $height;
    public string $stockInformation;
    public ?string $articleNumberFabric;
    public ?string $image;
    public ?string $datasheet;
    public string $extraDeliveryTimeInfo;
    public bool $expiringItem;

    /** @var array<\NatecSdk\Resources\Price> */
    public array $prices;

    /**
     * @param array<string, mixed> $data
     * @throws \NatecSdk\Exceptions\NatecSdkException
     */
    public function __construct(array $data)
    {
        /** @var array<array<string, mixed>> $prices */
        $prices = $data['prices'];

        foreach ($prices as $price) {
            $this->prices[] = new Price($price);
        }

        unset($data['prices']);

        parent::__construct($data);
    }

    /**
     * @codeCoverageIgnore
     */
    public static function endpoint(): string
    {
        return '/products';
    }
}
