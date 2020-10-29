<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.3/Deprecation-90260-ResourceFactorygetInstancePseudo-factory.html
 */
final class SubstituteResourceFactoryRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, ResourceFactory::class)) {
            return null;
        }
        if (! $this->isName($node->name, 'getInstance')) {
            return null;
        }
        return $this->createStaticCall(
            GeneralUtility::class,
            'makeInstance',
            [$this->createClassConstantReference(ResourceFactory::class)]
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Substitue ResourceFactory::getInstance() through GeneralUtility::makeInstance(ResourceFactory::class)',
            [
                new CodeSample(<<<'PHP'
$resourceFactory = ResourceFactory::getInstance();
PHP
, <<<'PHP'
$resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
PHP
),
            ]
        );
    }
}
