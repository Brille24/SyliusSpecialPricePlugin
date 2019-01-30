<?php
declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Entity;


use Sylius\Component\Core\Model\ProductVariantInterface;

class ChannelSpecialPricing implements ChannelSpecialPricingInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var ProductVariantInterface
     */
    protected $productVariant;

    /**
     * @var int
     */
    protected $price;

    /**
     * @var string
     */
    protected $channelCode;

    /**
     * @var \DateTimeInterface
     */
    protected $startsAt;

    /**
     * @var \DateTimeInterface
     */
    protected $endsAt;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductVariant(): ?ProductVariantInterface
    {
        return $this->productVariant;
    }

    /**
     * {@inheritdoc}
     */
    public function setProductVariant(?ProductVariantInterface $productVariant): void
    {
        $this->productVariant = $productVariant;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * {@inheritdoc}
     */
    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    /**
     * {@inheritdoc}
     */
    public function getChannelCode(): ?string
    {
        return $this->channelCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setChannelCode(?string $channelCode): void
    {
        $this->channelCode = $channelCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getStartsAt(): ?\DateTimeInterface
    {
        return $this->startsAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setStartsAt(?\DateTimeInterface $startsAt): void
    {
        $this->startsAt = $startsAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getEndsAt(): ?\DateTimeInterface
    {
        return $this->endsAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setEndsAt(?\DateTimeInterface $endsAt): void
    {
        $this->endsAt = $endsAt;
    }
}
