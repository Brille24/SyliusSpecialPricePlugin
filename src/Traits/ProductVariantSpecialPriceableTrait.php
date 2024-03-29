<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Traits;

use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Validator\Constraints as Assert;

trait ProductVariantSpecialPriceableTrait
{
    #[ORM\OneToMany(
        mappedBy: "productVariant",
        targetEntity: "Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface",
        cascade: ['all'],
        orphanRemoval: true,
    )]
    #[Assert\Valid]
    protected Collection $channelSpecialPricings;

    public function __construct()
    {
        $this->initSpecialPricings();
    }

    public function initSpecialPricings(): void
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
    public function setChannelSpecialPricings(array $channelSpecialPricings): void
    {
        $this->channelSpecialPricings = new ArrayCollection($channelSpecialPricings);
    }

    /**
     * {@inheritdoc}
     */
    public function getChannelSpecialPricingsForChannel(ChannelInterface $channel): Collection
    {
        return $this->channelSpecialPricings->filter(function (ChannelSpecialPricingInterface $specialPricing) use ($channel) {
            return $specialPricing->getChannelCode() === $channel->getCode();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getChannelSpecialPricingForChannelAndDate(ChannelInterface $channel, ?\DateTime $dateTime = null): ?ChannelSpecialPricingInterface
    {
        if (null === $dateTime) {
            $dateTime = new \DateTime('now');
        }

        $specialPricings = $this->getChannelSpecialPricingsForChannel($channel);

        /** @var ChannelSpecialPricingInterface $specialPricing */
        foreach ($specialPricings as $specialPricing) {
            if ($specialPricing->isActive($dateTime)) {
                return $specialPricing;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function hasChannelSpecialPricingsForChannel(ChannelInterface $channel): bool
    {
        return null !== $this->getChannelSpecialPricingsForChannel($channel);
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
        $this->channelSpecialPricings->add($channelSpecialPricing);
    }

    /**
     * {@inheritdoc}
     */
    public function removeChannelSpecialPricing(ChannelSpecialPricingInterface $channelSpecialPricing): void
    {
        $this->channelSpecialPricings->removeElement($channelSpecialPricing);
    }
}
