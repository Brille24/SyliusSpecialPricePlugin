<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Menu;

use Sylius\Bundle\AdminBundle\Event\ProductVariantMenuBuilderEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'sylius.menu.admin.product_variant.form', method: 'addItems')]
final class AdminProductVariantFormMenuListener
{
    public function addItems(ProductVariantMenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $specialPricingsTab = '@Brille24SyliusSpecialPricePlugin/Admin/ProductVariant/Tab/_specialPricings.html.twig';
        $menu
            ->addChild('channelSpecialPricings')
            ->setAttribute('template', $specialPricingsTab)
            ->setLabel('brille24.form.channel_special_price.special_prices')
            ->setLabelAttribute('icon', 'dollar');
    }
}
