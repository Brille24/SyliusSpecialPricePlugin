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
        $this->getDocument()->find('css', sprintf('.menu .item[data-tab="%s"]', $channel->getCode()))->click();

        $collection = $this->getDocument()->find('css', sprintf('#sylius_product_variant_channelSpecialPricings_%s', $channel->getCode()));

        $collection->clickLink('Add');
    }

    /**
     * @param ChannelInterface $channel
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function removeSpecialPriceForChannel(ChannelInterface $channel): void
    {
        $this->getDocument()->find('css', sprintf('.menu .item[data-tab="%s"]', $channel->getCode()))->click();

        $collection = $this->getDocument()->find('css', sprintf('#sylius_product_variant_channelSpecialPricings_%s', $channel->getCode()));

        $collection->clickLink('Delete');
    }

    /**
     * @param int $price
     * @param ChannelInterface $channel
     */
    public function setPrice(int $price, ChannelInterface $channel): void
    {
        $collection = $this->getDocument()->find('css', sprintf('#sylius_product_variant_channelSpecialPricings_%s', $channel->getCode()));
        $priceFields = $collection->findAll('css', 'input[id$="_price"]');
        $field = end($priceFields);

        $field->setValue($price);
    }

    /**
     * @param \DateTime $dateTime
     * @param ChannelInterface $channel
     */
    public function setStartDate(\DateTime $dateTime, ChannelInterface $channel): void
    {
        $collection = $this->getDocument()->find('css', sprintf('#sylius_product_variant_channelSpecialPricings_%s', $channel->getCode()));
        $items = $collection->findAll('css', '[data-form-collection="item"]');
        $item = end($items);

        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');

        $item->find('css', '[id$="startsAt_date_year"]')->setValue($year);
        $item->find('css', '[id$="startsAt_date_month"]')->setValue($month);
        $item->find('css', '[id$="startsAt_date_day"]')->setValue($day);

        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');

        $item->find('css', '[id$="startsAt_time_hour"]')->setValue($hour);
        $item->find('css', '[id$="startsAt_time_minute"]')->setValue($minute);
    }

    /**
     * @param \DateTime $dateTime
     * @param ChannelInterface $channel
     */
    public function setEndDate(\DateTime $dateTime, ChannelInterface $channel): void
    {
        $collection = $this->getDocument()->find('css', sprintf('#sylius_product_variant_channelSpecialPricings_%s', $channel->getCode()));
        $items = $collection->findAll('css', '[data-form-collection="item"]');
        $item = end($items);

        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');

        $item->find('css', '[id$="endsAt_date_year"]')->setValue($year);
        $item->find('css', '[id$="endsAt_date_month"]')->setValue($month);
        $item->find('css', '[id$="endsAt_date_day"]')->setValue($day);

        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');

        $item->find('css', '[id$="endsAt_time_hour"]')->setValue($hour);
        $item->find('css', '[id$="endsAt_time_minute"]')->setValue($minute);
    }

    /**
     * @return string
     */
    public function getSpecialPricesValidationMessage(): string
    {
        $element = $this->getDocument()->find('css', '#special_prices .sylius-validation-error');

        return $element->getText();
    }
}
