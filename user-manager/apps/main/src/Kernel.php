<?php

declare(strict_types=1);

namespace UserManager\Apps\Main;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseHttpKernel;

class Kernel extends BaseHttpKernel
{
    public function registerBundles(): iterable
    {
        $contents = require $this->getRootDir() . '/config/bundles.php';

        foreach ($contents as $class => $envs) {
            if ($envs[$this->getEnvironment()] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }
    }

    /**
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function getRootDir()
    {
        return \dirname(__DIR__);
    }

    /**
     * @inherit
     * @throws \Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(function (ContainerBuilder $container) use ($loader) {
            $container->addResource(new FileResource($this->getRootDir() . '/config/bundles.php'));

            $confDir = $this->getRootDir() . '/config';

            $loader->load($confDir . '/packages/', 'directory');
            $loader->load($confDir . '/packages/' . $this->environment . '/', 'directory');
        });
    }
}
