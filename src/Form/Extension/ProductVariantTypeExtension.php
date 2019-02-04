<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Form\Extension;

use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface;
use Brille24\SyliusSpecialPricePlugin\Entity\ProductVariantInterface;
use Brille24\SyliusSpecialPricePlugin\Form\Type\ChannelSpecialPricingType;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Bundle\CoreBundle\Form\Type\ChannelCollectionType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantType;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
            /** @var ProductVariantInterface|null $productVariant */
            $productVariant = $event->getData();

            $event->getForm()->add('channelSpecialPricings', ChannelCollectionType::class, [
                'mapped' => false,
                'entry_type' => CollectionType::class,
                'entry_options' => function (ChannelInterface $channel) use ($productVariant) {
                    // Get all special prices by channel
                    $specialPrices = $productVariant->hasChannelSpecialPricingForChannel($channel) ?
                        [$productVariant->getChannelSpecialPricingForChannel($channel)] : [];

                    return [
                        'entry_type' => ChannelSpecialPricingType::class,
                        'entry_options' => [
                            'channel' => $channel,
                            'product_variant' => $productVariant,
                            'required' => false,
                        ],
                        'allow_add' => true,
                        'allow_delete' => true,
                        'data' => $specialPrices,
                    ];
                },
                'label' => 'sylius.form.variant.special_price',
            ]);
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event): void {
            /** @var ProductVariantInterface $productVariant */
            $productVariant = $event->getData();
            $formSpecialPricings = $event->getForm()->get('channelSpecialPricings')->getData();

            $specialPricings = new ArrayCollection();
            foreach ($formSpecialPricings as $channeledFormSpecialPricing) {
                /** @var ChannelSpecialPricingInterface $formSpecialPricing */
                foreach ($channeledFormSpecialPricing as $formSpecialPricing) {
                    $specialPricings->set($formSpecialPricing->getChannelCode(), $formSpecialPricing);
                }
            }

            $productVariant->setChannelSpecialPricings($specialPricings);
        });
    }

    public function getExtendedType()
    {
        return ProductVariantType::class;
    }
}
