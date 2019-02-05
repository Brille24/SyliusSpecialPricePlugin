<?php

declare(strict_types=1);

namespace spec\Brille24\SyliusSpecialPricePlugin\Calculator;

use Brille24\SyliusSpecialPricePlugin\Calculator\SpecialPriceCalculator;
use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface;
use Brille24\SyliusSpecialPricePlugin\Entity\ProductVariantInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\ChannelInterface;

class SpecialPriceCalculatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SpecialPriceCalculator::class);
    }

    function let(
        ProductVariantPriceCalculatorInterface $productVariantPriceCalculator
    ): void {
        $this->beConstructedWith($productVariantPriceCalculator);
    }

    function it_implements_interface(): void
    {
        $this->shouldImplement(ProductVariantPriceCalculatorInterface::class);
    }

    function it_uses_active_special_price(
        ProductVariantInterface $productVariant,
        ChannelSpecialPricingInterface $specialPricing,
        ChannelInterface $channel
    ): void {
        $productVariant->getChannelSpecialPricingForChannelAndDate($channel, Argument::any())->willReturn($specialPricing);

        $specialPricing->getPrice()->willReturn(123);

        $this->calculate($productVariant, ['channel' => $channel])->shouldReturn(123);
    }

    function it_uses_decorated_calculator_if_no_special_price_is_active(
        ProductVariantInterface $productVariant,
        ProductVariantPriceCalculatorInterface $productVariantPriceCalculator,
        ChannelInterface $channel
    ): void {
        $productVariant->getChannelSpecialPricingForChannelAndDate($channel, Argument::any())->willReturn(null);

        $context = ['channel' => $channel];

        $productVariantPriceCalculator->calculate($productVariant, $context)->shouldBeCalled();
        $productVariantPriceCalculator->calculate($productVariant, $context)->willReturn(666);

        $this->calculate($productVariant, $context)->shouldReturn(666);
    }
}
