<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Form\Extension;

use Brille24\SyliusSpecialPricePlugin\Form\Type\ChannelSpecialPricingType;
use Sylius\Bundle\CoreBundle\Form\Type\ChannelCollectionType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantType;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ProductVariantTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $productVariant = $event->getData();

            $event->getForm()->add('channelSpecialPricings', ChannelCollectionType::class, [
                'entry_type' => ChannelSpecialPricingType::class,
                'entry_options' => function (ChannelInterface $channel) use ($productVariant) {
                    return [
                        'channel' => $channel,
                        'product_variant' => $productVariant,
                        'required' => false,
                    ];
                },
                'label' => 'sylius.form.variant.price',
            ]);
        });
    }

    public function getExtendedType()
    {
        return ProductVariantType::class;
    }
}
