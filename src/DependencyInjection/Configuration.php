<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress UnusedVariable
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('brille24_sylius_special_price_plugin');
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
