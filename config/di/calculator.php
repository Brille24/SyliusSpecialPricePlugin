<?php

declare(strict_types=1);

use Brille24\SyliusSpecialPricePlugin\Calculator\SpecialPriceCalculator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(SpecialPriceCalculator::class)
        ->decorate('sylius.calculator.product_variant_price')
        ->args([service('.inner')])
    ;
};
