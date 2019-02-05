<?php

namespace spec\Brille24\SyliusSpecialPricePlugin\Validator;

use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricing;
use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface;
use Brille24\SyliusSpecialPricePlugin\Entity\ProductVariantInterface;
use Brille24\SyliusSpecialPricePlugin\Validator\ProductVariantChannelSpecialPriceDateOverlapConstraint;
use Brille24\SyliusSpecialPricePlugin\Validator\ProductVariantChannelSpecialPriceDateOverlapValidator;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class ProductVariantChannelSpecialPriceDateOverlapValidatorSpec extends ObjectBehavior
{
    function let(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $violationBuilder
    ): void {
        $violationBuilder->atPath(Argument::any())->willReturn($violationBuilder);
        $context->buildViolation(Argument::any())->willReturn($violationBuilder);

        $this->initialize($context);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductVariantChannelSpecialPriceDateOverlapValidator::class);
    }

    function it_is_a_validator()
    {
        $this->shouldBeAnInstanceOf(ConstraintValidator::class);
    }

    function it_does_not_add_violation_if_dates_dont_overlap(
        ProductVariantInterface $productVariant,
        ProductVariantChannelSpecialPriceDateOverlapConstraint $constraint,
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $violationBuilder
    ): void {
        $firstPrice = new ChannelSpecialPricing();
        $firstPrice->setStartsAt(new \DateTime('2 days ago'));
        $firstPrice->setEndsAt(new \DateTime('yesterday'));
        $firstPrice->setChannelCode('FIRST');

        $secondPrice = new ChannelSpecialPricing();
        $secondPrice->setStartsAt(new \DateTime('now'));
        $secondPrice->setEndsAt(new \DateTime('tomorrow'));
        $secondPrice->setChannelCode('FIRST');

        $productVariant->getChannelSpecialPricings()->willReturn(new ArrayCollection([$firstPrice, $secondPrice]));;

        $context->buildViolation(Argument::any())->shouldNotBeCalled();
        $violationBuilder->atPath(Argument::any())->shouldNotBeCalled();
        $violationBuilder->addViolation()->shouldNotBeCalled();

        $this->validate($productVariant, $constraint);
    }

    function it_adds_violation_if_dates_overlap(
        ProductVariantInterface $productVariant,
        ProductVariantChannelSpecialPriceDateOverlapConstraint $constraint,
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $violationBuilder
    ): void {
        $firstPrice = new ChannelSpecialPricing();
        $firstPrice->setStartsAt(new \DateTime('2 days ago'));
        $firstPrice->setEndsAt(new \DateTime('tomorrow'));
        $firstPrice->setChannelCode('FIRST');

        $secondPrice = new ChannelSpecialPricing();
        $secondPrice->setStartsAt(new \DateTime('now'));
        $secondPrice->setEndsAt(new \DateTime('tomorrow'));
        $secondPrice->setChannelCode('FIRST');

        $productVariant->getChannelSpecialPricings()->willReturn(new ArrayCollection([$firstPrice, $secondPrice]));

        $context->buildViolation(Argument::any())->shouldBeCalled();
        $violationBuilder->atPath(Argument::any())->shouldBeCalled();
        $violationBuilder->addViolation()->shouldBeCalled();

        $this->validate($productVariant, $constraint);
    }
}
