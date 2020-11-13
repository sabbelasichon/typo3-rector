<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.3/Deprecation-84984-ProtectedUserTSconfigPropertiesInBackendUserAuthentication.html
 */
final class PropertyUserTsToMethodGetTsConfigOfBackendUserAuthenticationRector extends AbstractRector
{
    /**
     * @var Typo3NodeResolver
     */
    private $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    public function getNodeTypes(): array
    {
        return [PropertyFetch::class];
    }

    /**
     * @param PropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (! $this->isName($node->name, 'userTS')) {
            return null;
        }

        return $this->createMethodCall($node->var, 'getTSConfig');
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use method getTSConfig instead of property userTS', [
            new CodeSample(<<<'PHP'
if(is_array($GLOBALS['BE_USER']->userTS['tx_news.']) && $GLOBALS['BE_USER']->userTS['tx_news.']['singleCategoryAcl'] === '1') {
    return true;
}
PHP
                , <<<'PHP'
if(is_array($GLOBALS['BE_USER']->getTSConfig()['tx_news.']) && $GLOBALS['BE_USER']->getTSConfig()['tx_news.']['singleCategoryAcl'] === '1') {
    return true;
}
PHP
            ),
        ]);
    }

    private function shouldSkip(PropertyFetch $node): bool
    {
        if ($this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals($node, Typo3NodeResolver::BACKEND_USER)) {
            return false;
        }
        return ! $this->isObjectType($node->var, BackendUserAuthentication::class);
    }
}
