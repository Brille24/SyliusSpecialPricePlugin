<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Form\Type;

use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

class ChannelSpecialPricingType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        Assert::isInstanceOf($options['channel'], ChannelInterface::class);
        Assert::isInstanceOf($options['channel']->getBaseCurrency(), CurrencyInterface::class);
        Assert::nullOrIsInstanceOf($options['product_variant'], ProductVariantInterface::class);

        /** @psalm-suppress PossiblyNullReference */
        $builder
            ->add('price', MoneyType::class, [
                'label'    => 'brille24.form.channel_special_price.price',
                'currency' => $options['channel']->getBaseCurrency()->getCode(),
            ])
            ->add('startsAt', DateTimeType::class, [
                'label'    => 'brille24.form.channel_special_price.starts_at',
                'required' => false,
            ])
            ->add('endsAt', DateTimeType::class, [
                'label'    => 'brille24.form.channel_special_price.ends_at',
                'required' => false,
            ])
        ;

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options): void {
            $channelSpecialPricing = $event->getData();

            if (!$channelSpecialPricing instanceof $this->dataClass || !$channelSpecialPricing instanceof ChannelSpecialPricingInterface) {
                $event->setData(null);

                return;
            }

            $channelSpecialPricing->setChannelCode($options['channel']->getCode());
            $channelSpecialPricing->setProductVariant($options['product_variant']);

            $event->setData($channelSpecialPricing);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired('channel')
            ->setAllowedTypes('channel', [ChannelInterface::class])

            ->setDefined('product_variant')
            ->setAllowedTypes('product_variant', ['null', ProductVariantInterface::class])

            ->setDefaults([
                'label' => function (Options $options): string {
                    Assert::isInstanceOf($options['channel'], ChannelInterface::class);

                    $channelName = $options['channel']->getName();
                    Assert::string($channelName);

                    return $channelName;
                },
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'brille24_channel_special_pricing';
    }
}
