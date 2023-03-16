<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Entity;

use DateTimeInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

/** @psalm-suppress MissingConstructor */
class ChannelSpecialPricing implements ChannelSpecialPricingInterface
{
    protected int $id;

    protected ?ProductVariantInterface $productVariant = null;

    protected ?int $price = null;

    protected ?string $channelCode = null;

    protected ?DateTimeInterface $startsAt = null;

    protected ?DateTimeInterface $endsAt = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getProductVariant(): ?ProductVariantInterface
    {
        return $this->productVariant;
    }

    public function setProductVariant(?ProductVariantInterface $productVariant): void
    {
        $this->productVariant = $productVariant;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    public function getChannelCode(): ?string
    {
        return $this->channelCode;
    }

    public function setChannelCode(?string $channelCode): void
    {
        $this->channelCode = $channelCode;
    }

    public function getStartsAt(): ?\DateTimeInterface
    {
        return $this->startsAt;
    }

    public function setStartsAt(?\DateTimeInterface $startsAt): void
    {
        $this->startsAt = $startsAt;
    }

    public function getEndsAt(): ?\DateTimeInterface
    {
        return $this->endsAt;
    }

    public function setEndsAt(?\DateTimeInterface $endsAt): void
    {
        $this->endsAt = $endsAt;
    }

    public function isActive(?\DateTimeInterface $dateTime = null): bool
    {
        if (null === $dateTime) {
            $dateTime = new \DateTime('now');
        }

        // Account for null start and end
        if ($this->startsAt === null && $this->endsAt === null) {
            return true;
        }

        // Account for null start
        if ($this->startsAt === null && $dateTime < $this->endsAt) {
            return true;
        }

        // Account for null end
        if ($this->endsAt === null && $dateTime >= $this->startsAt) {
            return true;
        }

        // Check if $dateTime is between start and end date of the pricing.
        if ($dateTime >= $this->startsAt &&
            $dateTime < $this->endsAt) {
            return true;
        }

        return false;
    }
}
