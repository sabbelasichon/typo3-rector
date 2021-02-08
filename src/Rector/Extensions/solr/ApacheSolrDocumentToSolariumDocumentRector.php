<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Extensions\solr;

use Apache_Solr_Document;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
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
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, Apache_Solr_Document::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'setMultiValue')) {
            return null;
        }

        $node->name = new Identifier('addField');

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Apache_Solr_Document to solarium based document', [new CodeSample(<<<'PHP'
PHP
            , <<<'PHP'
PHP
        )]);
    }
}
