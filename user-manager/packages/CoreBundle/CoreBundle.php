<?php

declare(strict_types=1);

namespace UserManager\CoreBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use UserManager\Core\Context\Domain\DomainEvent\DomainEventPublisher;
use UserManager\CoreBundle\DependencyInjection\CompilerPass\CoreCompilerPass;
use UserManager\CoreBundle\DependencyInjection\CompilerPass\TestCompilerPass;

class CoreBundle extends Bundle
{
    /**
     * @var DomainEventPublisher
     */
    private $domainEventPublisher;

    public function build(ContainerBuilder $container): void
    {
        $env = $container->getParameter('kernel.environment');

        $container->addCompilerPass(new CoreCompilerPass());

        if ('test' === $env) {
            $container->addCompilerPass(new TestCompilerPass());
        }
    }

    public function boot(): void
    {
        /** @var DomainEventPublisher $domainEventPublisher */
        $domainEventPublisher = $this->container->get('user_manager.event.publisher.__init_on_get__');
        $this->domainEventPublisher = $domainEventPublisher;
    }

    public function shutdown(): void
    {
        $this->domainEventPublisher::close();
    }
}
