<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SupportPatches\App;

use Composer;
use Magento\SupportPatches\Filesystem\DirectoryList;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @inheritDoc
 *
 * @codeCoverageIgnore
 */
class Container implements ContainerInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    private $container;

    /**
     * @param string $basePath
     * @param string $magentoBasePath
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @throws \Exception
     */
    public function __construct(string $basePath, string $magentoBasePath)
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->set('container', $containerBuilder);
        $containerBuilder->setDefinition('container', new Definition(__CLASS__))
            ->setArguments([$basePath, $magentoBasePath]);

        $directoryList = new DirectoryList($basePath, $magentoBasePath);
        $composer = $this->createComposerInstance($directoryList);

        $containerBuilder->set(DirectoryList::class, $directoryList);
        $containerBuilder->setDefinition(DirectoryList::class, new Definition(DirectoryList::class));

        $containerBuilder->set(Composer\Composer::class, $composer);
        $containerBuilder->setDefinition(Composer\Composer::class, new Definition(Composer\Composer::class));

        $loader = new XmlFileLoader($containerBuilder, new FileLocator($basePath . '/config'));
        $loader->load('services.xml');
        $containerBuilder->compile();

        $this->container = $containerBuilder;
    }

    /**
     * @param DirectoryList $directoryList
     * @return Composer\Composer
     */
    private function createComposerInstance(DirectoryList $directoryList): Composer\Composer
    {
        $composerFactory = new Composer\Factory();
        $composerFile = file_exists($directoryList->getMagentoRoot() . '/composer.json')
            ? $directoryList->getMagentoRoot() . '/composer.json'
            : $directoryList->getRoot() . '/composer.json';

        $composer = $composerFactory->createComposer(
            new Composer\IO\BufferIO(),
            $composerFile,
            false,
            $directoryList->getMagentoRoot()
        );

        return $composer;
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * @inheritdoc
     */
    public function has($id): bool
    {
        return $this->container->has($id);
    }
}
