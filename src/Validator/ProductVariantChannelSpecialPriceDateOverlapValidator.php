<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Validator;

use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface;
use Brille24\SyliusSpecialPricePlugin\Traits\ProductVariantSpecialPriceableInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

class ProductVariantChannelSpecialPriceDateOverlapValidator extends ConstraintValidator
{
    /**
     * @psalm-suppress ParamNameMismatch
     */
    public function validate(mixed $productVariant, Constraint $constraint): void
    {
        Assert::isInstanceOf($productVariant, ProductVariantSpecialPriceableInterface::class);
        Assert::isInstanceOf($constraint, ProductVariantChannelSpecialPriceDateOverlapConstraint::class);

        $specialPricesByChannel = [];

        foreach ($productVariant->getChannelSpecialPricings() as $channelSpecialPricing) {
            /** @psalm-suppress PossiblyNullArrayOffset */
            $specialPricesByChannel[$channelSpecialPricing->getChannelCode()][] = $channelSpecialPricing;
        }

        foreach ($specialPricesByChannel as $specialPrices) {
            // Check for all specialPrices, if their dates overlap.
            foreach ($specialPrices as $a) {
                foreach ($specialPrices as $b) {
                    if ($this->datesOverlap($a, $b)) {
                        $this->context->buildViolation($constraint->message)
                            ->atPath('channelSpecialPricings')
                            ->addViolation()
                        ;

                        return;
                    }
                }
            }
        }
    }

    /**
     * @param ChannelSpecialPricingInterface $a
     * @param ChannelSpecialPricingInterface $b
     *
     * @return bool
     */
    private function datesOverlap(ChannelSpecialPricingInterface $a, ChannelSpecialPricingInterface $b): bool
    {
        // Don't compare a pricing with itself
        if ($a === $b) {
            return false;
        }

        if ($this->overlapWithNullValues($a, $b) || $this->overlapWithNullValues($b, $a)) {
            return true;
        }

        // All dates are DateTime objects
        if (!($a->getStartsAt() >= $b->getEndsAt() || $a->getEndsAt() <= $b->getStartsAt())) {
            return true;
        }

        return false;
    }

    /**
     * @param ChannelSpecialPricingInterface $a
     * @param ChannelSpecialPricingInterface $b
     *
     * @return bool
     */
    private function overlapWithNullValues(ChannelSpecialPricingInterface $a, ChannelSpecialPricingInterface $b): bool
    {
        // Both dates null = always active
        if ($a->getStartsAt() === null && $a->getEndsAt() === null) {
            return true;
        }

        // Start null = active till end
        if ($a->getStartsAt() === null) {
            if ($b->getStartsAt() < $a->getEndsAt()) {
                return true;
            }
        }

        // End null = active since start
        if ($a->getEndsAt() === null) {
            if ($b->getEndsAt() > $a->getStartsAt()) {
                return true;
            }
        }

        return false;
    }
}
