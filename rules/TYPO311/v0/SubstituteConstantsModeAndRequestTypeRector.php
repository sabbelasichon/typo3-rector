<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\BitwiseAnd;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.0/Deprecation-92947-DeprecateTYPO3_MODEAndTYPO3_REQUESTTYPEConstants.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v0\SubstituteConstantsModeAndRequestTypeRector\SubstituteConstantsModeAndRequestTypeRectorTest
 */
final class SubstituteConstantsModeAndRequestTypeRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private FilesFinder $filesFinder;

    /**
     * @readonly
     */
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
            // Handles standalone TYPO3_REQUESTTYPE_FE or TYPO3_REQUESTTYPE_BE constants
            return $this->refactorStandaloneRequestTypeConstant($node);
        }

        if ($node instanceof BitwiseAnd) {
            // Handles TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_FE/BE
            return $this->refactorBitwiseRequestType($node);
        }

        if ($node instanceof Identical || $node instanceof NotIdentical) {
            // Handles TYPO3_MODE === 'FE' or TYPO3_MODE !== 'FE' etc.
            return $this->refactorTypo3ModeComparison($node);
        }

        return null;
    }

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
            new CodeSample(
                <<<'CODE_SAMPLE'
if (TYPO3_MODE === 'FE') {
    // Do something
}
if (TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_FE) {
    // Do something
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Http\ApplicationType;

if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend()) {
    // Do something
}
if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend()) {
    // Do something
}
CODE_SAMPLE
            ),
            new CodeSample(
                <<<'CODE_SAMPLE'
if (!(TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_BE)) {
    // Do something
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Http\ApplicationType;

if (!(ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isBackend())) {
    // Do something
}
CODE_SAMPLE
            ),
            new CodeSample(
                <<<'CODE_SAMPLE'
if (TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_CLI) {
    // Do something
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Core\Environment;

if (Environment::isCli()) {
    // Do something
}
CODE_SAMPLE
            ),
        ]);
    }

    private function refactorProbablySecurityGuard(FuncCall $node): ?Node
    {
        if (! $this->isName($node, 'defined')) {
            return null;
        }

        if (! isset($node->args[0])) {
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

    /**
     * Handles TYPO3_MODE === 'FE', TYPO3_MODE === 'BE',
     * TYPO3_MODE !== 'FE', TYPO3_MODE !== 'BE'
     */
    private function refactorTypo3ModeComparison(BinaryOp $node): ?Node
    {
        if (! $node instanceof Identical && ! $node instanceof NotIdentical) {
            return null;
        }

        if ($this->shouldSkip()) {
            return null;
        }

        if ($node->left instanceof ConstFetch && $node->right instanceof String_) {
            $constFetchNode = $node->left;
            $stringNode = $node->right;
        } elseif ($node->right instanceof ConstFetch && $node->left instanceof String_) {
            $constFetchNode = $node->right;
            $stringNode = $node->left;
        } else {
            return null;
        }

        if (! $this->isName($constFetchNode, 'TYPO3_MODE')) {
            return null;
        }

        $typeValue = $this->valueResolver->getValue($stringNode);

        if (! in_array($typeValue, ['FE', 'BE'], true)) {
            return null;
        }

        $methodCall = ($typeValue === 'BE')
            ? $this->createIsBackendCall()
            : $this->createIsFrontendCall();

        // If original operator was !==, we need to negate the result
        if ($node instanceof NotIdentical) {
            return new BooleanNot($methodCall);
        }

        return $methodCall;
    }

    /**
     * Handles TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_FE or
     * TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_BE
     */
    private function refactorBitwiseRequestType(BitwiseAnd $node): ?Node
    {
        $leftOperand = $node->left;
        $rightOperand = $node->right;

        if ($leftOperand instanceof ConstFetch
            && $rightOperand instanceof ConstFetch
            && $this->isName($leftOperand, 'TYPO3_REQUESTTYPE')
        ) {
            $requestTypeConstantNode = $rightOperand;
        } elseif ($rightOperand instanceof ConstFetch
            && $leftOperand instanceof ConstFetch
            && $this->isName($rightOperand, 'TYPO3_REQUESTTYPE')
        ) {
            $requestTypeConstantNode = $leftOperand;
        } else {
            return null;
        }

        // At this point, $typo3RequestTypeNode is TYPO3_REQUESTTYPE
        // and $requestTypeConstantNode is the other constant.
        if ($this->isName($requestTypeConstantNode, 'TYPO3_REQUESTTYPE_FE')) {
            return $this->createIsFrontendCall();
        }

        if ($this->isName($requestTypeConstantNode, 'TYPO3_REQUESTTYPE_BE')) {
            return $this->createIsBackendCall();
        }

        if ($this->isName($requestTypeConstantNode, 'TYPO3_REQUESTTYPE_CLI')) {
            return $this->createIsCliCall();
        }

        return null;
    }

    /**
     * Handles standalone TYPO3_REQUESTTYPE_FE or TYPO3_REQUESTTYPE_BE constants.
     * These could be assigned to variables or used in other contexts.
     */
    private function refactorStandaloneRequestTypeConstant(ConstFetch $node): ?Node
    {
        if ($this->isName($node, 'TYPO3_REQUESTTYPE_FE')) {
            return $this->createIsFrontendCall();
        }

        if ($this->isName($node, 'TYPO3_REQUESTTYPE_BE')) {
            return $this->createIsBackendCall();
        }

        if ($this->isName($node, 'TYPO3_REQUESTTYPE_CLI')) {
            return $this->createIsCliCall();
        }

        return null;
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

    private function createIsCliCall(): StaticCall
    {
        return $this->nodeFactory->createStaticCall(
            'TYPO3\CMS\Core\Core\Environment',
            'isCli',
        );
    }

    private function shouldSkip(): bool
    {
        $filePath = $this->file->getFilePath();
        if ($this->filesFinder->isExtLocalConf($filePath)) {
            return true;
        }

        return $this->filesFinder->isExtTables($filePath);
    }

    /**
     * @return ArrayDimFetch[]
     */
    private function createRequestArguments(): array
    {
        return [$this->nodeFactory->createArg(
            new ArrayDimFetch(new Variable(Typo3NodeResolver::GLOBALS), new String_('TYPO3_REQUEST'))
        )];
    }
}
