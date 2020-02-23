<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Frontend\Page;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.5/Deprecation-86338-ChangeVisibilityOfPageRepository-init.html
 */
final class RemoveInitMethodFromPageRepositoryRector extends AbstractRector
{
    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall|Node $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isMethodStaticCallOrClassMethodObjectType($node, PageRepository::class)) {
            return null;
        }

        if (!$this->isName($node->name, 'init')) {
            return null;
        }

        try {
            $this->removeNode($node);
        } catch (ShouldNotHappenException $e) {
            $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
            $this->removeNode($parentNode);
        }

        return null;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Remove method call init from PageRepository', [
            new CodeSample(
                <<<'PHP'
$repository = GeneralUtility::makeInstance(PageRepository::class);
$repository->init(true);
PHP
                ,
                <<<'PHP'
$repository = GeneralUtility::makeInstance(PageRepository::class);
PHP
            ),
        ]);
    }
}
