<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v5\v0;

use PhpParser\Node;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://docs.typo3.org/p/apache-solr-for-typo3/solr/10.0/en-us/Releases/solr-release-9-0.html
 */
final class ApacheSolrDocumentToSolariumDocumentRector extends AbstractRector
{
    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [];
    }

    public function refactor(Node $node): ?Node
    {
        // change the node
        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Move from Apache_Solr_Document to solarium based Document', [new CodeSample(<<<'PHP'
PHP
            , <<<'PHP'
PHP
        )]);
    }
}
