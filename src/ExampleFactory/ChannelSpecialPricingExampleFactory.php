<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\ExampleFactory;

use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricing;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ChannelSpecialPricingExampleFactory implements ExampleFactoryInterface
{
    /** @var ProductVariantRepositoryInterface */
    private $productVariantRepository;

    /** @var OptionsResolver */
    private $optionsResolver;

    /**
     * ChannelSpecialPricingExampleFactory constructor.
     *
     * @param ProductVariantRepositoryInterface $productVariantRepository
     */
    public function __construct(ProductVariantRepositoryInterface $productVariantRepository)
    {
        $this->productVariantRepository = $productVariantRepository;

        $this->optionsResolver = new OptionsResolver();
        $this->configureOptions($this->optionsResolver);
    }

    /** {@inheritdoc} */
    public function create(array $options = []): ChannelSpecialPricing
    {
        $options = $this->optionsResolver->resolve($options);

        $channelSpecialPricing = new ChannelSpecialPricing();

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

        $resolver->setDefined('startsAt')->setNormalizer('startsAt', function (Options $options, $previousValue): ?\DateTimeInterface {
            if (null === $previousValue || $previousValue instanceof \DateTime) {
                return $previousValue;
            }

            return new \DateTime($previousValue);
        });

        $resolver->setDefined('endsAt')->setNormalizer('endsAt', function (Options $options, $previousValue): ?\DateTimeInterface {
            if (null === $previousValue || $previousValue instanceof \DateTime) {
                return $previousValue;
            }

            return new \DateTime($previousValue);
        });
    }
}
