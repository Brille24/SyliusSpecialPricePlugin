<?php
declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Calculator;

use Brille24\SyliusSpecialPricePlugin\Entity\ProductVariantInterface;
use Sylius\Component\Core\Model\ProductVariantInterface as SyliusProductVariantInterface;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
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
     * @param array $context
     *
     * @throws \Exception
     */
    public function calculate(SyliusProductVariantInterface $productVariant, array $context): int
    {
        Assert::keyExists($context, 'channel');

        $specialPricing = $productVariant->getChannelSpecialPricingForChannel($context['channel']);

        $priceValid = false;
        if (null !== $specialPricing) {
            // Check if the date is valid
            $currentDate = new \DateTime('now');

            $priceValid = ($currentDate >= $specialPricing->getStartsAt() && $currentDate < $specialPricing->getEndsAt());
        }

        if (!$priceValid) {
            return $this->productVariantPriceCalculator->calculate($productVariant, $context);
        }

        return $specialPricing->getPrice();
    }
}
