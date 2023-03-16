<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\ExampleFactory;

use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricing;
use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

final class ChannelSpecialPricingExampleFactory implements ExampleFactoryInterface
{
    private OptionsResolver $optionsResolver;

    /**
     * @param ProductVariantRepositoryInterface $productVariantRepository
     * @param FactoryInterface<ChannelSpecialPricing> $channelSpeciarPricingFactory
     */
    public function __construct(
        private ProductVariantRepositoryInterface $productVariantRepository,
        private FactoryInterface $channelSpeciarPricingFactory,
    ) {
        $this->optionsResolver = new OptionsResolver();
        $this->configureOptions($this->optionsResolver);
    }

    /** {@inheritdoc} */
    public function create(array $options = []): ChannelSpecialPricingInterface
    {
        $options = $this->optionsResolver->resolve($options);

        Assert::isInstanceOf($options['variant'], ProductVariantInterface::class);
        Assert::string($options['channelCode']);
        Assert::integer($options['price']);
        Assert::nullOrIsInstanceOf($options['startsAt'], \DateTimeInterface::class);
        Assert::nullOrIsInstanceOf($options['endsAt'], \DateTimeInterface::class);

        $channelSpecialPricing = $this->channelSpeciarPricingFactory->createNew();

        $channelSpecialPricing->setProductVariant($options['variant']);
        $channelSpecialPricing->setChannelCode($options['channelCode']);
        $channelSpecialPricing->setPrice($options['price']);
        $channelSpecialPricing->setStartsAt($options['startsAt']);
        $channelSpecialPricing->setEndsAt($options['endsAt']);

        return $channelSpecialPricing;
    }

    /**
     * @param OptionsResolver $resolver
     */
    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired('variant')
            ->setNormalizer('variant', LazyOption::findOneBy($this->productVariantRepository, 'code'))
        ;

        $resolver->setRequired('channelCode')->setAllowedTypes('channelCode', 'string');
        $resolver->setRequired('price')->setAllowedTypes('price', 'integer');

        /**
         * @psalm-suppress UnusedClosureParam
         * @psalm-suppress MissingClosureParamType
         */
        $resolver->setDefined('startsAt')->setNormalizer('startsAt', function (Options $options, $previousValue): ?\DateTimeInterface {
            if (null === $previousValue || $previousValue instanceof \DateTime) {
                return $previousValue;
            }

            /** @var string $previousValue */
            return new \DateTime($previousValue);
        });

        /**
         * @psalm-suppress UnusedClosureParam
         * @psalm-suppress MissingClosureParamType
         */
        $resolver->setDefined('endsAt')->setNormalizer('endsAt', function (Options $options, $previousValue): ?\DateTimeInterface {
            if (null === $previousValue || $previousValue instanceof \DateTime) {
                return $previousValue;
            }

            /** @var string $previousValue */
            return new \DateTime($previousValue);
        });
    }
}
