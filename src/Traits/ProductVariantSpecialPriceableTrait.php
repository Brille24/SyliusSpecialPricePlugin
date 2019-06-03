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
    /**
     * @ORM\OneToMany(
     *     targetEntity="Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface",
     *     mappedBy="productVariant",
     *     orphanRemoval=true,
     *     cascade={"all"}
     * )
     *
     * @Assert\Valid()
     *
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
    public function setChannelSpecialPricings(array $channelSpecialPricings): void
    {
        $this->channelSpecialPricings = new ArrayCollection($channelSpecialPricings);
    }

    /**
     * {@inheritdoc}
     */
    public function getChannelSpecialPricingsForChannel(ChannelInterface $channel): Collection
    {
        $specialPricings = $this->channelSpecialPricings->filter(function (ChannelSpecialPricingInterface $specialPricing) use ($channel) {
            return $specialPricing->getChannelCode() === $channel->getCode();
        });

        return $specialPricings;
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
            // Account for null start and end
            if ($specialPricing->getStartsAt() === null && $specialPricing->getEndsAt() === null) {
                return $specialPricing;
            }

            // Account for null start
            if ($specialPricing->getStartsAt() === null && $dateTime < $specialPricing->getEndsAt()) {
                return $specialPricing;
            }

            // Account for null end
            if ($specialPricing->getEndsAt() === null && $dateTime >= $specialPricing->getStartsAt()) {
                return $specialPricing;
            }

            // Check if $dateTime is between start and end date of the pricing.
            if ($dateTime >= $specialPricing->getStartsAt() &&
                $dateTime < $specialPricing->getEndsAt()) {
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
