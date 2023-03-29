<?php

declare(strict_types=1);

use Brille24\SyliusSpecialPricePlugin\Form\Extension\ProductVariantTypeExtension;
use Brille24\SyliusSpecialPricePlugin\Form\Type\ChannelSpecialPricingType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantType;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ChannelSpecialPricingType::class)
        ->args([
            param('brille24.model.channel_special_pricing.class'),
            param('sylius.form.type.channel_pricing.validation_groups'),
        ])
        ->tag('form.type')
    ;

    $services->set(ProductVariantTypeExtension::class)
        ->tag('form.type_extension', ['extended-type' => ProductVariantType::class])
    ;
};
