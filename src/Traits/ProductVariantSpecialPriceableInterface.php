<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Traits;

use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ChannelInterface;

interface ProductVariantSpecialPriceableInterface
{
    /**
     * @return Collection
     */
    public function getChannelSpecialPricings(): Collection;

    /**
     * @param array $channelSpecialPricings
     */
    public function setChannelSpecialPricings(array $channelSpecialPricings): void;

    /**
     * @param ChannelInterface $channel
     *
     * @return Collection
     */
    public function getChannelSpecialPricingsForChannel(ChannelInterface $channel): Collection;

    /**
     * @param ChannelInterface $channel
     * @param \DateTime $dateTime
     *
     * @return ChannelSpecialPricingInterface|null
     */
    public function getChannelSpecialPricingForChannelAndDate(ChannelInterface $channel, ?\DateTime $dateTime = null): ?ChannelSpecialPricingInterface;

    /**
     * @param ChannelInterface $channel
     *
     * @return bool
     */
    public function hasChannelSpecialPricingsForChannel(ChannelInterface $channel): bool;

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
