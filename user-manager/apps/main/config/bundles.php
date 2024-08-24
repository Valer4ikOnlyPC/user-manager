<?php

declare(strict_types=1);

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\MonologBundle\MonologBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\DebugBundle\DebugBundle::class => [
        'dev' => true,
        'test' => true,
    ],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => [
        'all' => true,
    ],
    \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => [
        'all' => true,
    ],
    \UserManager\CoreBundle\CoreBundle::class => [
        'all' => true,
    ],
];
