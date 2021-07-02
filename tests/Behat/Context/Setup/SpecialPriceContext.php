<?php
declare(strict_types=1);

namespace Tests\Brille24\SyliusSpecialPricePlugin\Behat\Context\Setup;


use Behat\Behat\Context\Context;
use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Tests\Application\SyliusSpecialPricePlugin\Entity\ProductVariant;

class SpecialPriceContext implements Context
{
    /**
     * @var FactoryInterface
     */
    private $channelSpecialPriceFactory;
    /**
     * @var EntityManagerInterface
     */
    private $objectManager;

    public function __construct(
        FactoryInterface $channelSpecialPriceFactory,
        EntityManagerInterface $objectManager
    ) {
        $this->channelSpecialPriceFactory = $channelSpecialPriceFactory;
        $this->objectManager = $objectManager;
    }

    /**
     * @Given the :variant variant has a special price for channel :channel priced at :price thats valid from :startsDate till :endsDate
     */
    public function theVariantHasASpecialPriceForChannelPricedAtThatsValidFromTill(
        ProductVariant $variant,
        ChannelInterface $channel,
        int $price,
        \DateTime $startsDate,
        \DateTime $endsDate
    ) {
        /** @var ChannelSpecialPricingInterface $specialPrice */
        $specialPrice = $this->channelSpecialPriceFactory->createNew();

        $specialPrice->setPrice($price);
        $specialPrice->setProductVariant($variant);
        $specialPrice->setChannelCode($channel->getCode());
        $specialPrice->setStartsAt($startsDate);
        $specialPrice->setEndsAt($endsDate);

        $variant->addChannelSpecialPricing($specialPrice);

        $this->objectManager->flush();
    }
}
