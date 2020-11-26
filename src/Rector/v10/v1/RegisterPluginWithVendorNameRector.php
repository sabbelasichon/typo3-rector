<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v1;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\SmartFileSystem\SmartFileInfo;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.1/Deprecation-88995-CallingRegisterPluginWithVendorName.html
 */
final class RegisterPluginWithVendorNameRector extends AbstractRector
{
    /**
     * @return string[]
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
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, ExtensionUtility::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'registerPlugin')) {
            return null;
        }

        return $this->removeVendorNameIfNeeded($node);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Remove vendor name from registerPlugin call', [new CodeSample(<<<'CODE_SAMPLE'
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
   'TYPO3.CMS.Form',
   'Formframework',
   'Form',
   'content-form',
);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
   'Form',
   'Formframework',
   'Form',
   'content-form',
);
CODE_SAMPLE
)]);
    }

    private function removeVendorNameIfNeeded(StaticCall $node): ?Node
    {
        $extensionNameArgumentValue = $node->args[0]->value;

        $extensionName = $this->getValue($extensionNameArgumentValue);

        if ($extensionNameArgumentValue instanceof Concat && $this->isPotentiallyUndefinedExtensionKeyVariable(
                $extensionNameArgumentValue
            )) {
            /** @var SmartFileInfo $fileInfo */
            $fileInfo = $node->getAttribute(AttributeKey::FILE_INFO);

            $extensionName = $this->getValue($extensionNameArgumentValue->left) . basename(
                    $fileInfo->getRelativeDirectoryPath()
                );
        }

        if (! is_string($extensionName)) {
            return null;
        }

        $delimiterPosition = strrpos($extensionName, '.');
        if (false === $delimiterPosition) {
            return null;
        }

        $extensionName = substr($extensionName, $delimiterPosition + 1);
        $node->args[0] = $this->createArg($extensionName);
        return $node;
    }

    private function isPotentiallyUndefinedExtensionKeyVariable(Concat $extensionNameArgumentValue): bool
    {
        if (! $extensionNameArgumentValue->right instanceof Variable) {
            return false;
        }

        if (null !== $this->getValue($extensionNameArgumentValue->right)) {
            return false;
        }
        return $this->isNames($extensionNameArgumentValue->right, ['_EXTKEY', 'extensionKey']);
    }
}
