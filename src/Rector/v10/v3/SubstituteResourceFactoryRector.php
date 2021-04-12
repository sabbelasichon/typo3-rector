<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.3/Deprecation-90260-ResourceFactorygetInstancePseudo-factory.html
 */
final class SubstituteResourceFactoryRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(ResourceFactory::class)
        )) {
            return null;
        }
        if (! $this->isName($node->name, 'getInstance')) {
            return null;
        }
        return $this->nodeFactory->createStaticCall(
            GeneralUtility::class,
            'makeInstance',
            [$this->nodeFactory->createClassConstReference(ResourceFactory::class)]
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Substitue ResourceFactory::getInstance() through GeneralUtility::makeInstance(ResourceFactory::class)',
            [
                new CodeSample(<<<'CODE_SAMPLE'
$resourceFactory = ResourceFactory::getInstance();
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
CODE_SAMPLE
),
            ]
        );
    }
}
