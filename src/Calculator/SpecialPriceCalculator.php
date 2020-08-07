<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Calculator;

use Brille24\SyliusSpecialPricePlugin\Traits\ProductVariantSpecialPriceableInterface;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Webmozart\Assert\Assert;

class SpecialPriceCalculator implements ProductVariantPriceCalculatorInterface
{
    /**
     * @var ProductVariantPriceCalculatorInterface
     */
    private $productVariantPriceCalculator;

    public function __construct(ProductVariantPriceCalculatorInterface $productVariantPriceCalculator)
    {
        $this->productVariantPriceCalculator = $productVariantPriceCalculator;
    }

    /**
     * @param ProductVariantInterface $productVariant
     * @param array                   $context
     *
     * @return int
     */
    public function calculate(ProductVariantInterface $productVariant, array $context): int
    {
        Assert::keyExists($context, 'channel');

        $specialPricing = null;
        if ($productVariant instanceof ProductVariantSpecialPriceableInterface) {
            $specialPricing = $productVariant->getChannelSpecialPricingForChannelAndDate($context['channel']);
        }

        if (null === $specialPricing) {
            return $this->productVariantPriceCalculator->calculate($productVariant, $context);
        }

        $price = $specialPricing->getPrice();
        Assert::notNull($price);

        return $price;
    }
}
