<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Resource\File;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Deprecation-85895-DeprecateFile_getMetaData.html
 */
final class UseMetaDataAspectRector extends AbstractRector
{
    /**
     * @return array<class-string<\PhpParser\Node>>
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
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, File::class)) {
            return null;
        }
        if (! $this->isName($node->name, '_getMetaData')) {
            return null;
        }
        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall($node->var, 'getMetaData'),
            'get'
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use $fileObject->getMetaData()->get() instead of $fileObject->_getMetaData()', [
            new CodeSample(<<<'CODE_SAMPLE'
$fileObject = new File();
$fileObject->_getMetaData();
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$fileObject = new File();
$fileObject->getMetaData()->get();
CODE_SAMPLE
),
        ]);
    }
}
