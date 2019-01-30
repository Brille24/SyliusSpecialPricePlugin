<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Traits;

use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ChannelInterface;

interface ProductVariantSpecialPricableInterface
{
    /**
     * @return Collection
     */
    public function getChannelSpecialPricings(): Collection;

    /**
     * @param ChannelInterface $channel
     *
     * @return ChannelSpecialPricingInterface|null
     */
    public function getChannelSpecialPricingForChannel(ChannelInterface $channel): ?ChannelSpecialPricingInterface;

    /**
     * @param ChannelInterface $channel
     *
     * @return bool
     */
    public function hasChannelSpecialPricingForChannel(ChannelInterface $channel): bool;

    /**
     * @param ChannelSpecialPricingInterface $channelPricing
     *
     * @return bool
     */
    public function hasChannelSpecialPricing(ChannelSpecialPricingInterface $channelPricing): bool;

    /**
     * @param ChannelSpecialPricingInterface $channelPricing
     */
    public function addChannelSpecialPricing(ChannelSpecialPricingInterface $channelPricing): void;

    /**
     * @param ChannelSpecialPricingInterface $channelPricing
     */
    public function removeChannelSpecialPricing(ChannelSpecialPricingInterface $channelPricing): void;
}
