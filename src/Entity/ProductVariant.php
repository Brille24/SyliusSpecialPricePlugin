<?php
declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Entity;

use Brille24\SyliusSpecialPricePlugin\Traits\ProductVariantSpecialPricableInterface;
use Brille24\SyliusSpecialPricePlugin\Traits\ProductVariantSpecialPricableTrait;
use Sylius\Component\Core\Model\ProductVariant as SyliusProductVariant;
use Sylius\Component\Core\Model\ProductVariantInterface;

class ProductVariant extends SyliusProductVariant implements ProductVariantInterface, ProductVariantSpecialPricableInterface
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
