<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Validator;

use Symfony\Component\Validator\Constraint;

class ProductVariantChannelSpecialPriceDateOverlapConstraint extends Constraint
{
    /** @var string */
    public $message = 'brille24.product_variant.channel_special_pricing.dates_overlap';

    /**
     * {@inheritdoc}
     */
    public function validatedBy(): string
    {
        return 'brille24_special_price_date_overlap';
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets(): string
    {
        return Constraint::CLASS_CONSTRAINT;
    }
}
