<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typo3;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-96996-DeprecateTypoScriptFrontendController-checkEnableFields.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplaceTSFECheckEnableFieldsRector\ReplaceTSFECheckEnableFieldsRectorTest
 */
final class ReplaceTSFECheckEnableFieldsRector extends AbstractRector
{
    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    /**
     * @return array<class-string<Node>>
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (! $this->isNames($node->name, ['checkEnableFields', 'checkPagerecordForIncludeSection'])) {
            return null;
        }

        $contextCall = $this->nodeFactory->createMethodCall($node->var, 'getContext');

        $rowArgument = $node->args[0] ?? new Array_();

        if ($this->isName($node->name, 'checkEnableFields')) {
            $arguments = [new String_('pages'), $rowArgument, $contextCall];
            $replacementMethod = 'accessGranted';
        } else {
            $arguments = [$rowArgument, $contextCall];
            $replacementMethod = 'accessGrantedForPageInRootLine';
        }

        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall('TYPO3\CMS\Core\Utility\GeneralUtility', 'makeInstance', [
                $this->nodeFactory->createClassConstReference(
                    'TYPO3\CMS\Core\Domain\Access\RecordAccessVoter\RecordAccessVoter'
                ),
            ]),
            $replacementMethod,
            $arguments
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace TSFE calls to checkEnableFields with new RecordAccessVoter->accessGranted method ',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$row = [];

$foo = $GLOBALS['TSFE']->checkEnableFields($row);
$foofoo = $GLOBALS['TSFE']->checkPagerecordForIncludeSection($row);

/** @var TypoScriptFrontendController $typoscriptFrontendController */
$typoscriptFrontendController = $GLOBALS['TSFE'];
$bar = $typoscriptFrontendController->checkEnableFields($row);
$baz = $typoscriptFrontendController->checkPagerecordForIncludeSection($row);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Domain\Access\RecordAccessVoter\RecordAccessVoter;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

$row = [];

$foo = GeneralUtility::makeInstance(RecordAccessVoter::class)->accessGranted('pages', $row, $GLOBALS['TSFE']->getContext());
$foofoo = GeneralUtility::makeInstance(RecordAccessVoter::class)->accessGrantedForPageInRootLine($row, $GLOBALS['TSFE']->getContext());

/** @var TypoScriptFrontendController $typoscriptFrontendController */
$typoscriptFrontendController = $GLOBALS['TSFE'];
$bar = GeneralUtility::makeInstance(RecordAccessVoter::class)->accessGranted('pages', $row, $typoscriptFrontendController->getContext());
$baz = GeneralUtility::makeInstance(RecordAccessVoter::class)->accessGrantedForPageInRootLine($row, $typoscriptFrontendController->getContext());
CODE_SAMPLE
                ),
            ]
        );
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        if ($this->typo3NodeResolver->isAnyMethodCallOnGlobals(
            $methodCall,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        )) {
            return false;
        }

        return ! $this->isObjectType(
            $methodCall->var,
            new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
        );
    }
}
