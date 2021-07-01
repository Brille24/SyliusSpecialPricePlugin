<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Form\Extension;

use Brille24\SyliusSpecialPricePlugin\Form\Type\ChannelSpecialPricingType;
use Brille24\SyliusSpecialPricePlugin\Traits\ProductVariantSpecialPriceableInterface;
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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            /** @var ProductVariantSpecialPriceableInterface $productVariant */
            $productVariant = $event->getData();

            $event->getForm()->add('channelSpecialPricings', ChannelCollectionType::class, [
                'mapped'        => false,
                'entry_type'    => CollectionType::class,
                'entry_options' => function (ChannelInterface $channel) use ($productVariant) {
                    // Get all special prices by channel
                    $specialPrices = $productVariant->getChannelSpecialPricingsForChannel($channel);

                    return [
                        'entry_type'    => ChannelSpecialPricingType::class,
                        'entry_options' => [
                            'channel'         => $channel,
                            'product_variant' => $productVariant,
                            'required'        => false,
                        ],
                        'allow_add'    => true,
                        'allow_delete' => true,
                        'data'         => $specialPrices,
                    ];
                },
                'label' => 'brille24.form.channel_special_price.special_prices',
            ]);
        });

        // Since the channelSpecialPricings form isn't mapped, we have to update the collection manually.
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event): void {
            /** @var ProductVariantSpecialPriceableInterface $productVariant */
            $productVariant = $event->getData();
            $formSpecialPricings = $event->getForm()->get('channelSpecialPricings')->getData();

            /*
             * Remove all special pricings from the product variant.
             *
             * We can't just override the collection, because then doctrine would remove all entities contained in that from the database.
             */
            foreach ($productVariant->getChannelSpecialPricings() as $channelSpecialPricing) {
                $productVariant->removeChannelSpecialPricing($channelSpecialPricing);
            }

            // Add all special pricings from the form.
            foreach ($formSpecialPricings as $channeledFormSpecialPricing) {
                foreach ($channeledFormSpecialPricing as $formSpecialPricing) {
                    $productVariant->addChannelSpecialPricing($formSpecialPricing);
                }
            }
        });
    }

    public static function getExtendedTypes(): iterable
    {
        return [ProductVariantType::class];
    }
}
