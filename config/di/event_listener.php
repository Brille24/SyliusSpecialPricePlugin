<?php

declare(strict_types=1);

use Brille24\SyliusSpecialPricePlugin\Menu\AdminProductFormMenuListener;
use Brille24\SyliusSpecialPricePlugin\Menu\AdminProductVariantFormMenuListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(AdminProductFormMenuListener::class);

    $services->set(AdminProductVariantFormMenuListener::class);
};
