<?php

declare(strict_types=1);

namespace Tests\Application\SyliusSpecialPricePlugin\Entity;

use Brille24\SyliusSpecialPricePlugin\Traits\ProductVariantSpecialPriceableInterface;
use Brille24\SyliusSpecialPricePlugin\Traits\ProductVariantSpecialPriceableTrait;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\ProductVariant as SyliusProductVariant;
use Sylius\Component\Core\Model\ProductVariantInterface;

/**
 * @ORM\MappedSuperclass
 * @ORM\Table(name="sylius_product_variant")
 */
class ProductVariant extends SyliusProductVariant implements ProductVariantInterface, ProductVariantSpecialPriceableInterface
{
    use ProductVariantSpecialPriceableTrait {
        __construct as protected specialPricingConstructor;
    }

    public function __construct()
    {
        parent::__construct();

        $this->specialPricingConstructor();
    }
}
