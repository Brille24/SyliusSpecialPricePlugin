<?php

declare(strict_types=1);

namespace Tests\Application\SyliusSpecialPricePlugin\Entity;

use Brille24\SyliusSpecialPricePlugin\Traits\ProductVariantSpecialPriceableInterface;
use Brille24\SyliusSpecialPricePlugin\Traits\ProductVariantSpecialPriceableTrait;
use Brille24\SyliusSpecialPricePlugin\Validator\ProductVariantChannelSpecialPriceDateOverlapConstraint;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\ProductVariant as SyliusProductVariant;
use Sylius\Component\Core\Model\ProductVariantInterface;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_product_variant')]
#[ProductVariantChannelSpecialPriceDateOverlapConstraint(groups: ['sylius'])]
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
