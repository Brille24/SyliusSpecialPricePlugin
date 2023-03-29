<?php

declare(strict_types=1);

use Brille24\SyliusSpecialPricePlugin\ExampleFactory\ChannelSpecialPricingExampleFactory;
use Brille24\SyliusSpecialPricePlugin\Fixtures\ChannelSpecialPricingFixture;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ChannelSpecialPricingFixture::class)
        ->args([
            service('doctrine.orm.entity_manager'),
            service(ChannelSpecialPricingExampleFactory::class),
        ])
        ->tag('sylius_fixtures.fixture')
    ;

    $services->set(ChannelSpecialPricingExampleFactory::class)
        ->args([
            service('sylius.repository.product_variant'),
            service('brille24.factory.channel_special_pricing'),
        ])
    ;
};
