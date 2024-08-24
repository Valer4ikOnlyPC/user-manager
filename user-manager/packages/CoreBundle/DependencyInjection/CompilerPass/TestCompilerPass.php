<?php

declare(strict_types=1);

namespace UserManager\CoreBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use UserManager\Core\Context\Application\Service\ApplicationServiceInterface;

class TestCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $serviceIds = $container->getServiceIds();

        foreach ($serviceIds as $serviceId) {
            if (false === mb_strstr($serviceId, 'UserManager')) {
                continue;
            }

            $definition = $container->getDefinition($serviceId);
            /** @psalm-var class-string<mixed>|null $className */
            $className = $definition->getClass();

            if (null === $className) {
                continue;
            }

            try {
                $reflection = new \ReflectionClass($className);
                if (true === $reflection->implementsInterface(ApplicationServiceInterface::class)) {
                    $definition->setPublic(true);
                }
            } catch (\ReflectionException $e) {
            }
        }
    }
}
