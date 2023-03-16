<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Calculator;

use Brille24\SyliusSpecialPricePlugin\Traits\ProductVariantSpecialPriceableInterface;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Webmozart\Assert\Assert;

/**
 * @psalm-suppress DeprecatedInterface
 */
class SpecialPriceCalculator implements ProductVariantPricesCalculatorInterface
{
    public function __construct(private ProductVariantPricesCalculatorInterface $productVariantPriceCalculator)
    {
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
        Assert::isInstanceOf($context['channel'], ChannelInterface::class);

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

    public function calculateOriginal(ProductVariantInterface $productVariant, array $context): int
    {
        return $this->productVariantPriceCalculator->calculateOriginal($productVariant, $context);
    }
}
