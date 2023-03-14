<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Validator;

use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

class ChannelPricingDateValidator extends ConstraintValidator
{
    /**
     * @psalm-suppress ParamNameMismatch
     */
    public function validate(mixed $channelSpecialPricing, Constraint $constraint): void
    {
        Assert::isInstanceOf($constraint, ChannelPricingDateConstraint::class);
        Assert::isInstanceOf($channelSpecialPricing, ChannelSpecialPricingInterface::class);

        // If either start or end is open, the date is valid.
        if (null === $channelSpecialPricing->getStartsAt() || null === $channelSpecialPricing->getEndsAt()) {
            return;
        }

        if ($channelSpecialPricing->getStartsAt() > $channelSpecialPricing->getEndsAt()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('startsAt')
                ->addViolation()
            ;
        }
    }
}
