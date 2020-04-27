<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Backend\Domain\Repository\Localization;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Backend\Domain\Repository\Localization\LocalizationRepository;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.3/Breaking-84877-MethodsOfLocalizationRepositoryChanged.html
 */
final class RemoveColPosParameterRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, LocalizationRepository::class)) {
            return null;
        }

        if (! $this->isNames(
            $node->name,
            [
                'fetchOriginLanguage',
                'getLocalizedRecordCount',
                'fetchAvailableLanguages',
                'getRecordsToCopyDatabaseResult',
            ]
        )) {
            return null;
        }

        if (count($node->args) <= 1) {
            return null;
        }

        $this->removeNode($node->args[1]);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
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
