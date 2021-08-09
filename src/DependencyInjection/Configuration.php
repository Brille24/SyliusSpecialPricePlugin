<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\DependencyInjection;

use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricing;
use Brille24\SyliusSpecialPricePlugin\Entity\ChannelSpecialPricingInterface;
use Brille24\SyliusSpecialPricePlugin\Form\Type\ChannelSpecialPricingType;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('brille24_sylius_special_price_plugin');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        /**
         * @psalm-suppress PossiblyUndefinedMethod
         * @psalm-suppress MixedMethodCall
         * @psalm-suppress PossiblyNullReference
         * @psalm-suppress UnusedMethodCall
         */
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
            ->end()
        ;

        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
        /**
         * @psalm-suppress PossiblyUndefinedMethod
         * @psalm-suppress MixedMethodCall
         * @psalm-suppress PossiblyNullReference
         */
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('channel_special_pricing')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('model')->defaultValue(ChannelSpecialPricing::class)->cannotBeEmpty()->end()
                                    ->scalarNode('interface')->defaultValue(ChannelSpecialPricingInterface::class)->cannotBeEmpty()->end()
                                    ->scalarNode('controller')->defaultValue(ResourceController::class)->end()
                                    ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                    ->scalarNode('form')->defaultValue(ChannelSpecialPricingType::class)->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
