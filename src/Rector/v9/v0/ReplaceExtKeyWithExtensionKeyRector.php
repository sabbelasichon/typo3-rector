<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Ssch\TYPO3Rector\Helper\FilesFinder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Important-82692-GuidelinesForExtensionFiles.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v0\ReplaceExtKeyWithExtensionKeyRector\ReplaceExtKeyWithExtensionKeyRectorTest
 */
final class ReplaceExtKeyWithExtensionKeyRector extends AbstractRector
{
    public function __construct(
        private FilesFinder $filesFinder
    ) {
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace $_EXTKEY with extension key', [new CodeSample(
            <<<'CODE_SAMPLE'
ExtensionUtility::configurePlugin(
    'Foo.'.$_EXTKEY,
    'ArticleTeaser',
    [
        'FooBar' => 'baz',
    ]
);
CODE_SAMPLE
                ,
            <<<'CODE_SAMPLE'
ExtensionUtility::configurePlugin(
    'Foo.'.'bar',
    'ArticleTeaser',
    [
        'FooBar' => 'baz',
    ]
);
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Variable::class];
    }

    /**
     * @param Variable $node
     */
    public function refactor(Node $node): ?Node
    {
        $fileInfo = $this->file->getSmartFileInfo();

        if ($this->filesFinder->isExtEmconf($fileInfo)) {
            return null;
        }

        if (! $this->isExtensionKeyVariable($node)) {
            return null;
        }

        $extEmConf = $this->createExtensionKeyFromFolder($fileInfo);

        if (null === $extEmConf) {
            return null;
        }

        if ($this->isAssignment($node)) {
            return null;
        }

        $extensionKey = basename($extEmConf->getRealPathDirectory());

        return new String_($extensionKey);
    }

    private function isExtensionKeyVariable(Variable $variable): bool
    {
        return $this->isName($variable, '_EXTKEY');
    }

    private function createExtensionKeyFromFolder(SmartFileInfo $fileInfo): ?SmartFileInfo
    {
        return $this->filesFinder->findExtEmConfRelativeFromGivenFileInfo($fileInfo);
    }

    private function isAssignment(Variable $node): bool
    {
        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
        // Check if we have an assigment to the property, if so do not change it
        return $parentNode instanceof Assign && $parentNode->var === $node;
    }
}
