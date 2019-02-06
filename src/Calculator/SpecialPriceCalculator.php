<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Calculator;

use Brille24\SyliusSpecialPricePlugin\Traits\ProductVariantSpecialPricableInterface;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\ProductVariantInterface as SyliusProductVariantInterface;
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
     * @param ProductVariantSpecialPricableInterface $productVariant
     * @param array $context
     *
     * @return int
     *
     * @throws \Exception
     */
    public function calculate(SyliusProductVariantInterface $productVariant, array $context): int
    {
        Assert::keyExists($context, 'channel');

        $specialPricing = $productVariant->getChannelSpecialPricingForChannelAndDate($context['channel']);

        if (null === $specialPricing) {
            return $this->productVariantPriceCalculator->calculate($productVariant, $context);
        }

        return $specialPricing->getPrice();
    }
}
