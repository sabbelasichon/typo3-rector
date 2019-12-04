<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Backend\Domain\Repository\Localization;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Backend\Domain\Repository\Localization\LocalizationRepository;

final class RemoveColPosParameterRector extends AbstractRector
{
    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [Node\Expr\MethodCall::class];
    }

    /**
     * @param Node|Node\Expr\MethodCall $node
     *
     * @return Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node, LocalizationRepository::class)) {
            return null;
        }

        if (!$this->isNames($node, ['fetchOriginLanguage', 'getLocalizedRecordCount', 'fetchAvailableLanguages', 'getRecordsToCopyDatabaseResult'])) {
            return null;
        }

        if (count($node->args) <= 1) {
            return null;
        }

        $this->removeNode($node->args[1]);

        return $node;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Remove parameter colPos from methods.', [
            new CodeSample(
                <<<'PHP'
$someObject = GeneralUtility::makeInstance(LocalizationRepository::class);
$someObject->fetchOriginLanguage($pageId, $colPos, $localizedLanguage);
PHP
                ,
                <<<'PHP'
$someObject = GeneralUtility::makeInstance(LocalizationRepository::class);
$someObject->fetchOriginLanguage($pageId, $localizedLanguage);
PHP
            ),
        ]);
    }
}
