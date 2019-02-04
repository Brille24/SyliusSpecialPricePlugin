<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Form\Type;

use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChannelSpecialPricingType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price', MoneyType::class, [
                'label' => 'brille24.ui.special_price',
                'currency' => $options['channel']->getBaseCurrency()->getCode(),

            ])
            ->add('startsAt', DateTimeType::class)
            ->add('endsAt', DateTimeType::class)
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
                    return $options['channel']->getName();
                },
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'brille24_channel_special_pricing';
    }
}
