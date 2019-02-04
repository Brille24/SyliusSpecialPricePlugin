<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Validator;

use Symfony\Component\Validator\Constraint;

class ChannelPricingDateConstraint extends Constraint
{
    public $message = 'brille24.product_variant.channel_special_pricing.start_before_end';

    public function validatedBy()
    {
        return 'brille24_special_price_date';
    }

    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }
}
