<?php

declare(strict_types=1);

namespace Tests\Brille24\SyliusSpecialPricePlugin\Entity;

use Brille24\SyliusSpecialPricePlugin\Traits\ProductVariantSpecialPricableTrait;
use Sylius\Component\Core\Model\ProductVariant as SyliusProductVariant;

class ProductVariant extends SyliusProductVariant implements ProductVariantInterface
{
    use ProductVariantSpecialPricableTrait {
        __construct as protected specialPricingConstructor;
    }

    public function __construct()
    {
        parent::__construct();

        $this->specialPricingConstructor();
    }
}
