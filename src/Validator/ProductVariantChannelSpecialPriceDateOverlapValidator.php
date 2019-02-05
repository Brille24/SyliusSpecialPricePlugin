<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Validator;

use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface;
use Brille24\SyliusSpecialPricePlugin\Traits\ProductVariantSpecialPricableInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProductVariantChannelSpecialPriceDateOverlapValidator extends ConstraintValidator
{
    /**
     * @param ProductVariantSpecialPricableInterface $productVariant
     * @param Constraint $constraint
     */
    public function validate($productVariant, Constraint $constraint)
    {
        $specialPricesByChannel = [];

        /** @var ChannelSpecialPricingInterface $channelSpecialPricing */
        foreach ($productVariant->getChannelSpecialPricings() as $channelSpecialPricing) {
            $specialPricesByChannel[$channelSpecialPricing->getChannelCode()][] = $channelSpecialPricing;
        }

        foreach ($specialPricesByChannel as $specialPrices) {
            // Check for all specialPrices, if their dates overlap.
            foreach ($specialPrices as $a) {
                foreach ($specialPrices as $b) {
                    if (!$this->datesOk($a, $b)) {
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
    private function datesOk(ChannelSpecialPricingInterface $a, ChannelSpecialPricingInterface $b): bool
    {
        if ($a === $b) {
            return true;
        }

        if ($a->getStartsAt() > $b->getEndsAt() || $a->getEndsAt() < $b->getStartsAt()) {
            return true;
        }

        return false;
    }
}
