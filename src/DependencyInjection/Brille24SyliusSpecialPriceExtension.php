<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class Brille24SyliusSpecialPriceExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    /**
     * @psalm-suppress UnusedVariable
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));

        $loader->load('services.xml');
    }

    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new Configuration();
    }

    protected function getMigrationsNamespace(): string
    {
        return 'Brille24\SyliusSpecialPricePlugin\Migrations';
    }

    protected function getMigrationsDirectory(): string
    {
        return '@Brille24SyliusSpecialPricePlugin/Migrations';
    }

    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return ['Sylius\Bundle\CoreBundle\Migrations'];
    }

    public function prepend(ContainerBuilder $container): void
    {
        $config = $this->getCurrentConfiguration($container);

        $this->registerResources('brille24', 'doctrine/orm', $config['resources'], $container);

        $this->prependDoctrineMigrations($container);
        $this->prependDoctrineMapping($container);
    }

    private function prependDoctrineMapping(ContainerBuilder $container): void
    {
        $config = array_merge(...$container->getExtensionConfig('doctrine'));

        // do not register mappings if dbal not configured.
        if (!isset($config['dbal']) || !isset($config['orm'])) {
            return;
        }

        $container->prependExtensionConfig('doctrine', [
            'orm' => [
                'mappings' => [
                    'Brille24SyliusSpecialPricePlugin' => [
                        'type' => 'xml',
                        'dir' => $this->getPath($container, '/config/doctrine/'),
                        'is_bundle' => false,
                        'prefix' => 'Brille24\SyliusSpecialPricePlugin\Entity',
                        'alias' => 'Brille24SyliusSpecialPricePlugin',
                    ],
                ],
            ],
        ]);
    }

    private function getCurrentConfiguration(ContainerBuilder $container): array
    {
        /** @var ConfigurationInterface $configuration */
        $configuration = $this->getConfiguration([], $container);

        $configs = $container->getExtensionConfig($this->getAlias());

        return $this->processConfiguration($configuration, $configs);
    }

    private function getPath(ContainerBuilder $container, string $path): string
    {
        /** @var array<string, array<string, string>> $metadata */
        $metadata = $container->getParameter('kernel.bundles_metadata');

        return $metadata['Brille24SyliusSpecialPricePlugin']['path'] . $path;
    }
}
