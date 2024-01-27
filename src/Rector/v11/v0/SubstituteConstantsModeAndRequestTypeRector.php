<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.0/Deprecation-92947-DeprecateTYPO3_MODEAndTYPO3_REQUESTTYPEConstants.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v0\SubstituteConstantsModeAndRequestTypeRector\SubstituteConstantsModeAndRequestTypeRectorTest
 */
final class SubstituteConstantsModeAndRequestTypeRector extends AbstractRector
{
    /**
     * @readonly
     */
    private FilesFinder $filesFinder;

    private ValueResolver $valueResolver;

    public function __construct(FilesFinder $filesFinder, ValueResolver $valueResolver)
    {
        $this->filesFinder = $filesFinder;
        $this->valueResolver = $valueResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [BinaryOp::class, FuncCall::class, ConstFetch::class];
    }

    /**
     * @param BinaryOp|FuncCall|ConstFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof FuncCall) {
            return $this->refactorProbablySecurityGuard($node);
        }

        if ($node instanceof ConstFetch) {
            return $this->refactorRequestType($node);
        }

        if ($this->shouldSkip()) {
            return null;
        }

        $constFetch = null;
        $type = null;
        if ($node->left instanceof ConstFetch) {
            $constFetch = $node->left;
            $type = $node->right;
        } elseif ($node->right instanceof ConstFetch) {
            $constFetch = $node->right;
            $type = $node->left;
        }

        if (! $constFetch instanceof ConstFetch) {
            return null;
        }

        if (! $this->isName($constFetch->name, 'TYPO3_MODE')) {
            return null;
        }

        $typeValue = $this->valueResolver->getValue($type);

        if (! in_array($typeValue, ['FE', 'BE'], true)) {
            return null;
        }

        if ($typeValue === 'BE') {
            return $this->createIsBackendCall();
        }

        return $this->createIsFrontendCall();
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Substitute TYPO3_MODE and TYPO3_REQUESTTYPE constants', [
            new CodeSample(
                <<<'CODE_SAMPLE'
defined('TYPO3_MODE') or die();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
defined('TYPO3') or die();
CODE_SAMPLE
            ),
        ]);
    }

    private function refactorProbablySecurityGuard(FuncCall $node): ?Node
    {
        if (! $this->isName($node, 'defined')) {
            return null;
        }

        $firstArgument = $node->args[0]->value;

        if (! $firstArgument instanceof String_) {
            return null;
        }

        if (! $this->valueResolver->isValue($firstArgument, 'TYPO3_MODE')) {
            return null;
        }

        $node->args[0]->value = new String_('TYPO3');

        return $node;
    }

    private function createIsBackendCall(): MethodCall
    {
        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall(
                'TYPO3\CMS\Core\Http\ApplicationType',
                'fromRequest',
                $this->createRequestArguments()
            ),
            'isBackend'
        );
    }

    private function createIsFrontendCall(): MethodCall
    {
        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall(
                'TYPO3\CMS\Core\Http\ApplicationType',
                'fromRequest',
                $this->createRequestArguments()
            ),
            'isFrontend'
        );
    }

    private function shouldSkip(): bool
    {
        if ($this->filesFinder->isExtLocalConf($this->file->getFilePath())) {
            return true;
        }

        return $this->filesFinder->isExtTables($this->file->getFilePath());
    }

    private function refactorRequestType(ConstFetch $node): ?Node
    {
        if (! $this->isNames($node, ['TYPO3_MODE', 'TYPO3_REQUESTTYPE_FE', 'TYPO3_REQUESTTYPE_BE'])) {
            return null;
        }

        if ($this->isName($node, 'TYPO3_REQUESTTYPE_FE')) {
            return $this->createIsFrontendCall();
        }

        if ($this->isName($node, 'TYPO3_REQUESTTYPE_BE')) {
            return $this->createIsBackendCall();
        }

        return null;
    }

    /**
     * @return ArrayDimFetch[]
     */
    private function createRequestArguments(): array
    {
        return [new ArrayDimFetch(new Variable(Typo3NodeResolver::GLOBALS), new String_('TYPO3_REQUEST'))];
    }
}
