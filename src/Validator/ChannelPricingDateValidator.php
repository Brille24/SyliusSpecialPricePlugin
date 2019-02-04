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
     * @param ChannelSpecialPricingInterface $channelSpecialPricing
     * @param Constraint $constraint
     */
    public function validate($channelSpecialPricing, Constraint $constraint)
    {
        Assert::isInstanceOf($constraint, ChannelPricingDateConstraint::class);

        if ($channelSpecialPricing->getStartsAt() > $channelSpecialPricing->getEndsAt()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('startsAt')
                ->addViolation()
            ;
        }
    }
}
