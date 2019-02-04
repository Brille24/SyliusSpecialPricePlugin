<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Traits;

use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ChannelInterface;

trait ProductVariantSpecialPricableTrait
{
    /**
     * @var Collection
     */
    protected $channelSpecialPricings;

    public function __construct()
    {
        $this->channelSpecialPricings = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getChannelSpecialPricings(): Collection
    {
        return $this->channelSpecialPricings;
    }

    /**
     * {@inheritdoc}
     */
    public function setChannelSpecialPricings(Collection $channelSpecialPricings): void
    {
        $this->channelSpecialPricings = $channelSpecialPricings;
    }

    /**
     * {@inheritdoc}
     */
    public function getChannelSpecialPricingForChannel(ChannelInterface $channel): ?ChannelSpecialPricingInterface
    {
        if ($this->channelSpecialPricings->containsKey($channel->getCode())) {
            return $this->channelSpecialPricings->get($channel->getCode());
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function hasChannelSpecialPricingForChannel(ChannelInterface $channel): bool
    {
        return null !== $this->getChannelSpecialPricingForChannel($channel);
    }

    /**
     * {@inheritdoc}
     */
    public function hasChannelSpecialPricing(ChannelSpecialPricingInterface $channelSpecialPricing): bool
    {
        return $this->channelSpecialPricings->contains($channelSpecialPricing);
    }

    /**
     * {@inheritdoc}
     */
    public function addChannelSpecialPricing(ChannelSpecialPricingInterface $channelSpecialPricing): void
    {
        if (!$this->hasChannelSpecialPricing($channelSpecialPricing)) {
            $channelSpecialPricing->setProductVariant($this);
            $this->channelSpecialPricings->set($channelSpecialPricing->getChannelCode(), $channelSpecialPricing);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeChannelSpecialPricing(ChannelSpecialPricingInterface $channelSpecialPricing): void
    {
        if ($this->hasChannelSpecialPricing($channelSpecialPricing)) {
            $channelSpecialPricing->setProductVariant(null);
            $this->channelSpecialPricings->remove($channelSpecialPricing->getChannelCode());
        }
    }
}
