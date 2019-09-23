<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Fixtures;


use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

final class ChannelSpecialPricingFixture extends AbstractResourceFixture
{
    /** {@inheritdoc} */
    public function getName(): string
    {
        return 'brille24_channel_special_pricing';
    }

    /** {@inheritdoc} */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        $resourceNode
            ->children()
                ->scalarNode('variant')->cannotBeEmpty()->end()
                ->scalarNode('channelCode')->cannotBeEmpty()->end()
                ->integerNode('price')->min(0)->end()
                ->scalarNode('startsAt')->defaultNull()->end()
                ->scalarNode('endsAt')->defaultNull()->end()
            ->end()
        ;
    }
}
