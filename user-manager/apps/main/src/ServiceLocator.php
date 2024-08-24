<?php

declare(strict_types=1);

namespace UserManager\Apps\Main;

use Symfony\Component\DependencyInjection\ContainerInterface;

final class ServiceLocator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var self
     */
    private static $instance;

    private function __construct()
    {
        require dirname(__DIR__) . '/../bootstrap.php';

        $kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
        $kernel->boot();
        $this->setContainer($kernel->getContainer());
    }

    private function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    private static function getInstance(): self
    {
        if (false === isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @psalm-template RealInstanceType of object
     * @psalm-param class-string<RealInstanceType> $id
     * @psalm-return RealInstanceType
     * @throws \Exception
     */
    public static function getService(string $id): object
    {
        return self::getInstance()->container->get($id);
    }
}
