<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([__DIR__ . '/user-manager/packages', __DIR__ . '/tests']);

    $ecsConfig->ruleWithConfiguration(ArraySyntaxFixer::class, [
        'syntax' => 'short',
    ]);

    $ecsConfig->sets([
        SetList::SPACES,
        SetList::ARRAY,
        SetList::DOCBLOCK,
        SetList::PSR_12,
        SetList::STRICT,
        SetList::NAMESPACES,
    ]);

    $ecsConfig->skip([
        \PhpCsFixer\Fixer\Strict\StrictComparisonFixer::class => [
            'user-manager/packages/Core/Context/Infrastructure/Persistence/InMemory/Repository/InMemoryRepository.php',
        ],
        \PhpCsFixer\Fixer\Strict\StrictParamFixer::class => [
            'user-manager/packages/Core/Context/Infrastructure/Persistence/InMemory/Repository/InMemoryRepository.php',
        ],
    ]);

    $ecsConfig->parallel();
};
