<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\ComposerExtensionKeyResolver;

trait ExtensionKeyResolverTrait
{
    /**
     * @readonly
     */
    private ComposerExtensionKeyResolver $composerExtensionKeyResolver;

    /**
     * @param Concat|String_ $contentArgumentValue
     */
    private function resolvePotentialExtensionKey($contentArgumentValue): void
    {
        if ($contentArgumentValue instanceof String_) {
            return;
        }

        if (! $contentArgumentValue->left instanceof Concat) {
            return;
        }

        if (! $contentArgumentValue->left->right instanceof Variable) {
            return;
        }

        if (! $this->isNames($contentArgumentValue->left->right, ['_EXTKEY', 'extensionKey'])) {
            return;
        }

        $resolvedExtensionKey = $this->composerExtensionKeyResolver->resolveExtensionKey($this->file);

        if ($resolvedExtensionKey === null) {
            return;
        }

        $contentArgumentValue->left->right = new String_($resolvedExtensionKey);
    }
}
