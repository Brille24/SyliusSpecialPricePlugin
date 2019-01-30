<?php
declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Entity;


use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface ChannelSpecialPricingInterface extends ResourceInterface
{
    /**
     * @return int
     */
    public function getPrice(): int;

    /**
     * @param int $price
     */
    public function setPrice(int $price): void;

    /**
     * @return ProductVariantInterface
     */
    public function getProductVariant(): ProductVariantInterface;

    /**
     * @param ProductVariantInterface $productVariant
     */
    public function setProductVariant(ProductVariantInterface $productVariant): void;

    /**
     * @return string
     */
    public function getChannelCode(): string ;

    /**
     * @param string $channelCode
     */
    public function setChannelCode(string $channelCode): void;

    /**
     * @return \DateTimeInterface
     */
    public function getStartsAt(): \DateTimeInterface;

    /**
     * @param \DateTimeInterface $startsAt
     */
    public function setStartsAt(\DateTimeInterface $startsAt): void;

    /**
     * @return \DateTimeInterface
     */
    public function getEndsAt(): \DateTimeInterface;

    /**
     * @param \DateTimeInterface $endsAt
     */
    public function setEndsAt(\DateTimeInterface $endsAt): void;
}
