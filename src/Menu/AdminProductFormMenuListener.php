<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Menu;


use Sylius\Bundle\AdminBundle\Event\ProductMenuBuilderEvent;

final class AdminProductFormMenuListener
{
    /**
     * @param ProductMenuBuilderEvent $event
     */
    public function addItems(ProductMenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        if ($event->getProduct()->isConfigurable()) {
            return;
        }

        $specialPricingsTab = '@Brille24SyliusSpecialPricePlugin/Resources/views/Admin/ProductVariant/Tab/_specialPricings.html.twig';
        $menu
            ->addChild('channelSpecialPricings')
            ->setAttribute('template', $specialPricingsTab)
            ->setLabel('brille24.form.channel_special_price.special_prices')
            ->setLabelAttribute('icon', 'dollar');
    }
}
