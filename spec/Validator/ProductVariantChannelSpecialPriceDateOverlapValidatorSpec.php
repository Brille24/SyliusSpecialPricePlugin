<?php

declare(strict_types=1);

namespace spec\Brille24\SyliusSpecialPricePlugin\Validator;

use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricing;
use Brille24\SyliusSpecialPricePlugin\Validator\ProductVariantChannelSpecialPriceDateOverlapConstraint;
use Brille24\SyliusSpecialPricePlugin\Validator\ProductVariantChannelSpecialPriceDateOverlapValidator;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use Tests\Application\SyliusSpecialPricePlugin\Entity\ProductVariant;

class ProductVariantChannelSpecialPriceDateOverlapValidatorSpec extends ObjectBehavior
{
    public function let(
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $violationBuilder
    ): void {
        $violationBuilder->atPath(Argument::any())->willReturn($violationBuilder);
        $context->buildViolation(Argument::any())->willReturn($violationBuilder);

        $this->initialize($context);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ProductVariantChannelSpecialPriceDateOverlapValidator::class);
    }

    public function it_is_a_validator()
    {
        $this->shouldBeAnInstanceOf(ConstraintValidator::class);
    }

    public function it_does_not_add_violation_if_dates_dont_overlap(
        ProductVariant $productVariant,
        ProductVariantChannelSpecialPriceDateOverlapConstraint $constraint,
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $violationBuilder
    ): void {
        foreach ($this->notOverlappingDatesProvider() as $dates) {
            $productVariant->getChannelSpecialPricings()->willReturn(new ArrayCollection($this->getChannelPrices($dates)));

            $context->buildViolation(Argument::any())->shouldNotBeCalled();
            $violationBuilder->atPath(Argument::any())->shouldNotBeCalled();
            $violationBuilder->addViolation()->shouldNotBeCalled();

            $this->validate($productVariant, $constraint);
        }
    }

    public function it_adds_violation_if_dates_overlap(
        ProductVariant $productVariant,
        ProductVariantChannelSpecialPriceDateOverlapConstraint $constraint,
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $violationBuilder
    ): void {
        foreach ($this->overlappingDatesProvider() as $dates) {
            $productVariant->getChannelSpecialPricings()->willReturn(new ArrayCollection($this->getChannelPrices($dates)));

            $context->buildViolation(Argument::any())->shouldBeCalled();
            $violationBuilder->atPath(Argument::any())->shouldBeCalled();
            $violationBuilder->addViolation()->shouldBeCalled();

            $this->validate($productVariant, $constraint);
        }
    }

    private function getChannelPrices(array $dates): array
    {
        [$firstStart, $firstEnd, $secondStart, $secondEnd] = $dates;

        $firstPrice = new ChannelSpecialPricing();
        $firstPrice->setStartsAt($firstStart);
        $firstPrice->setEndsAt($firstEnd);
        $firstPrice->setChannelCode('FIRST');

        $secondPrice = new ChannelSpecialPricing();
        $secondPrice->setStartsAt($secondStart);
        $secondPrice->setEndsAt($secondEnd);
        $secondPrice->setChannelCode('FIRST');

        return [$firstPrice, $secondPrice];
    }

    private function overlappingDatesProvider(): array
    {
        return [
            [null, null, null, null],
            [null, null, null, new \DateTime('now')],
            [null, null, new \DateTime('now'), null],
            [new \DateTime('now'), new \DateTime('tomorrow'), new \DateTime('yesterday'), new \DateTime('tomorrow')],
            [new \DateTime('now'), new \DateTime('tomorrow'), new \DateTime('yesterday'), null],
            [new \DateTime('now'), new \DateTime('tomorrow'), null, new \DateTime('tomorrow')],
            [new \DateTime('now'), null, null, new \DateTime('tomorrow')],
        ];
    }

    private function notOverlappingDatesProvider(): array
    {
        return [
            [new \DateTime('tomorrow'), null, null, new \DateTime('now')],
            [new \DateTime('yesterday'), new \DateTime('now'), new \DateTime('+1 minute'), new \DateTime('tomorrow')],
        ];
    }
}
