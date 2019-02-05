<?php

declare(strict_types=1);

namespace Tests\Brille24\SyliusSpecialPricePlugin\Entity;

use Brille24\SyliusSpecialPricePlugin\Traits\ProductVariantSpecialPricableInterface;

interface ProductVariantInterface extends ProductVariantSpecialPricableInterface, \Sylius\Component\Core\Model\ProductVariantInterface
{
}
