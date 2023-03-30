<?php

declare(strict_types=1);

use Brille24\SyliusSpecialPricePlugin\Menu\AdminProductFormMenuListener;
use Brille24\SyliusSpecialPricePlugin\Menu\AdminProductVariantFormMenuListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(AdminProductFormMenuListener::class)
        ->tag('kernel.event_listener', ['event' => 'sylius.menu.admin.product.form', 'method' => 'addItems'])
    ;

    $services->set(AdminProductVariantFormMenuListener::class)
        ->tag('kernel.event_listener', ['event' => 'sylius.menu.admin.product_variant.form', 'method' => 'addItems'])
    ;
};
