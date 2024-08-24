<?php

declare(strict_types=1);

namespace UserManager\CoreBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use UserManager\Core\Context\Domain\DomainEvent\DomainEventPublisher;

class CoreCompilerPass implements CompilerPassInterface
{
    public const DOMAIN_EVENT_SUBSCRIBER_TAG = 'user_manager.event.subscriber';

    public const ENTITY_MANAGER_AWARE_TAG = 'user_manager.entity_manager.aware';

    public function process(ContainerBuilder $container): void
    {
        $subscriberServices = $container->findTaggedServiceIds(self::DOMAIN_EVENT_SUBSCRIBER_TAG);

        $subscribers = [];
        foreach ($subscriberServices as $serviceId => $tags) {
            $subscribers[$serviceId] = new Reference($serviceId);
            $def = $container->getDefinition($serviceId);
            $def->clearTag(self::DOMAIN_EVENT_SUBSCRIBER_TAG);
        }

        $container->register('user_manager.event.publisher.__init_on_get__', DomainEventPublisher::class)
            ->setFactory([DomainEventPublisher::class, 'setInitialSubscribers'])
            ->setArguments([$subscribers])
            ->setPublic(true);

        $entityManagerAwareServices = $container->findTaggedServiceIds(self::ENTITY_MANAGER_AWARE_TAG);

        foreach ($entityManagerAwareServices as $serviceId => $tags) {
            $def = $container->getDefinition($serviceId);
            $def->clearTag(self::ENTITY_MANAGER_AWARE_TAG);
            $def->addMethodCall('setEntityManager', [new Reference('user_manager.entity_manager')]);
        }
    }
}
