<?php

namespace spec\Brille24\SyliusSpecialPricePlugin\Validator;

use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface;
use Brille24\SyliusSpecialPricePlugin\Validator\ChannelPricingDateConstraint;
use Brille24\SyliusSpecialPricePlugin\Validator\ChannelPricingDateValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class ChannelPricingDateValidatorSpec extends ObjectBehavior
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
        $this->shouldHaveType(ChannelPricingDateValidator::class);
    }

    function it_is_a_validator()
    {
        $this->shouldBeAnInstanceOf(ConstraintValidator::class);
    }

    function it_does_not_add_violation_if_dates_ok(
        ChannelSpecialPricingInterface $specialPricing,
        ChannelPricingDateConstraint $constraint,
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $violationBuilder
    ): void {
        $specialPricing->getStartsAt()->willReturn(new \DateTime('now'));
        $specialPricing->getEndsAt()->willReturn(new \DateTime('tomorrow'));

        $specialPricing->getStartsAt()->shouldBeCalled();
        $specialPricing->getEndsAt()->shouldBeCalled();

        $context->buildViolation(Argument::any())->shouldNotBeCalled();
        $violationBuilder->atPath(Argument::any())->shouldNotBeCalled();
        $violationBuilder->addViolation()->shouldNotBeCalled();

        $this->validate($specialPricing, $constraint);
    }

    function it_adds_violation_if_dates_not_in_order(
        ChannelSpecialPricingInterface $specialPricing,
        ChannelPricingDateConstraint $constraint,
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $violationBuilder
    ): void {
        $specialPricing->getStartsAt()->willReturn(new \DateTime('now'));
        $specialPricing->getEndsAt()->willReturn(new \DateTime('yesterday'));

        $specialPricing->getStartsAt()->shouldBeCalled();
        $specialPricing->getEndsAt()->shouldBeCalled();

        $context->buildViolation(Argument::any())->shouldBeCalled();
        $violationBuilder->atPath(Argument::any())->shouldBeCalled();
        $violationBuilder->addViolation()->shouldBeCalled();

        $this->validate($specialPricing, $constraint);
    }
}
