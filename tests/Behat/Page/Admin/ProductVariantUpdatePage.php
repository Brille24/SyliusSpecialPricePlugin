<?php
declare(strict_types=1);

namespace Tests\Brille24\SyliusSpecialPricePlugin\Behat\Page\Admin;


use Sylius\Behat\Page\Admin\ProductVariant\UpdatePage;
use Sylius\Component\Core\Model\ChannelInterface;

class ProductVariantUpdatePage extends UpdatePage
{
    /**
     * @param ChannelInterface $channel
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function addSpecialPriceForChannel(ChannelInterface $channel): void
    {
        $collection = $this->getDocument()->find('css', sprintf('#sylius_product_variant_channelSpecialPricings_%s', $channel->getCode()));

        $collection->clickLink('Add');
    }

    public function setPrice(int $price, ChannelInterface $channel): void
    {
        $collection = $this->getDocument()->find('css', sprintf('#sylius_product_variant_channelSpecialPricings_%s', $channel->getCode()));

        $priceFields = $collection->findAll('css', 'input[id$="_price"]');

        $field = end($priceFields);

        $field->setValue($price);
    }

    public function setStartDate(\DateTime $dateTime, ChannelInterface $channel): void
    {
        $collection = $this->getDocument()->find('css', sprintf('#sylius_product_variant_channelSpecialPricings_%s', $channel->getCode()));
    }
}
