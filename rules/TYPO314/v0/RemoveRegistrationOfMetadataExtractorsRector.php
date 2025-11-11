<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-107783-RemovedRegisterExtractionService.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveRegistrationOfMetadataExtractorsRector\RemoveRegistrationOfMetadataExtractorsRectorTest
 */
final class RemoveRegistrationOfMetadataExtractorsRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove Registration of Metadata Extractors via `ExtractorRegistry->registerExtractionService()`',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$extractorRegistry = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\Index\ExtractorRegistry::class);
$extractorRegistry->registerExtractionService(MyExtractor::class);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
-
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node): ?int
    {
        if (! $node->expr instanceof MethodCall) {
            return null;
        }

        if ($this->shouldSkip($node->expr)) {
            return null;
        }

        return NodeVisitor::REMOVE_NODE;
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        if (! $this->isName($methodCall->name, 'registerExtractionService')) {
            return true;
        }

        return ! $this->isObjectType(
            $methodCall->var,
            new ObjectType('TYPO3\CMS\Core\Resource\Index\ExtractorRegistry')
        );
    }
}
