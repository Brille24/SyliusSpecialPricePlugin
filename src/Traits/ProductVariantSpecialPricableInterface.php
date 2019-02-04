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
     * @param Collection $channelSpecialPricings
     */
    public function setChannelSpecialPricings(Collection $channelSpecialPricings): void;

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
     * @param ChannelSpecialPricingInterface $channelSpecialPricing
     *
     * @return bool
     */
    public function hasChannelSpecialPricing(ChannelSpecialPricingInterface $channelSpecialPricing): bool;

    /**
     * @param ChannelSpecialPricingInterface $channelSpecialPricing
     */
    public function addChannelSpecialPricing(ChannelSpecialPricingInterface $channelSpecialPricing): void;

    /**
     * @param ChannelSpecialPricingInterface $channelSpecialPricing
     */
    public function removeChannelSpecialPricing(ChannelSpecialPricingInterface $channelSpecialPricing): void;
}
