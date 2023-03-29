<?php

declare(strict_types=1);

use Brille24\SyliusSpecialPricePlugin\Calculator\SpecialPriceCalculator;
use Brille24\SyliusSpecialPricePlugin\Validator\ChannelPricingDateValidator;
use Brille24\SyliusSpecialPricePlugin\Validator\ProductVariantChannelSpecialPriceDateOverlapValidator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ChannelPricingDateValidator::class)
        ->tag('validator.constraint_validator', ['alias' => 'brille24_special_price_date'])
    ;

    $services->set(ProductVariantChannelSpecialPriceDateOverlapValidator::class)
        ->tag('validator.constraint_validator', ['alias' => 'brille24_special_price_date_overlap'])
    ;
};
