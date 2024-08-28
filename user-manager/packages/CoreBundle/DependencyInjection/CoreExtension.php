<?php

declare(strict_types=1);

namespace UserManager\CoreBundle\DependencyInjection;

use Composer\Autoload\ClassLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use UserManager\Core\Context\Infrastructure\Persistence\Doctrine\Type\Photo\DoctrinePhotoID;
use UserManager\Core\Context\Infrastructure\Persistence\Doctrine\Type\User\DoctrineUserID;
use UserManager\CoreBundle\Exception\LogicException;

class CoreExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container): void
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (! isset($bundles['DoctrineBundle'])) {
            throw new LogicException(
                'The DoctrineBundle is not registered in your application. Try running "composer require doctrine/doctrine-bundle".'
            );
        }

        /** @var string $composerLoaderFilename */
        $composerLoaderFilename = (new \ReflectionClass(ClassLoader::class))->getFileName();
        $vendorPath = dirname($composerLoaderFilename);
        /** @noinspection PhpIncludeInspection */
        $psr4PathsMap = require "$vendorPath/autoload_psr4.php";
        $corePath = reset($psr4PathsMap['UserManager\\Core\\']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('parameters.xml');

        foreach ($container->getExtensions() as $extensionName => $extensionConfigs) {
            switch ($extensionName) {
                case 'doctrine':
                    $config = [
                        'dbal' => [
                            'default-connection' => 'user_manager',
                            'connection' => [
                                'name' => 'user_manager',
                                'dbname' => '%env.user_manager.db_database%',
                                'user' => '%env.user_manager.db_user%',
                                'password' => '%env.user_manager.db_password%',
                                'host' => '%env.user_manager.db_host%',
                                'port' => '%env.user_manager.db_port%',
                                'driver' => '%env.user_manager.db_driver%',
                                'server_version' => '%env.user_manager.db_server_version%',
                            ],
                            'type' => $this->doctrineTypes(),
                        ],
                        'orm' => [
                            'default-entity-manager' => 'user_manager',
                            'entity-managers' => [
                                'user_manager' => [
                                    'mappings' => [
                                        'Core/Context/Domain' => [
                                            'prefix' => 'UserManager\Core\Context\Domain',
                                            'dir' => "$corePath/Context/Infrastructure/Persistence/Doctrine/Mapping/Domain",
                                            'is-bundle' => false,
                                            'type' => 'xml',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ];
                    $container->prependExtensionConfig($extensionName, $config);
                    break;
                case 'doctrine_migrations':
                    $config = [
                        'dir_name' => "$corePath/Context/Infrastructure/Persistence/Doctrine/Migration",
                        'namespace' => 'UserManager\Core\Context\Infrastructure\Persistence\Doctrine\Migration',
                    ];
                    $container->prependExtensionConfig($extensionName, $config);
                    break;
            }
        }
    }

    /**
     * @param mixed[] $configs
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $files = [
            'repo/entity_manager.xml',
            'repo/repo.xml',
            'service/service_user.xml',
            'service/service_security.xml',
            'service/service_photo.xml',
        ];

        foreach ($files as $file) {
            $loader->load($file);
        }
    }

    /**
     * @return array<int, mixed>
     */
    private function doctrineTypes(): array
    {
        $types = [
            DoctrineUserID::class,
            DoctrinePhotoID::class,
        ];

        return array_map(
            static function (string $typeClassName) {
                return [
                    'name' => $typeClassName::NAME,
                    'value' => $typeClassName,
                ];
            },
            $types
        );
    }
}
