<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Backend\Utility;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Information\Typo3Information;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.2/Deprecation-89756-BackendUtilityTYPO3_copyRightNotice.html
 */
final class UseTypo3InformationForCopyRightNoticeRector extends AbstractRector
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
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, BackendUtility::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'TYPO3_copyRightNotice')) {
            return null;
        }

        $staticCall = $this->createStaticCall(GeneralUtility::class, 'makeInstance', [
            $this->createClassConstantReference(Typo3Information::class),
        ]);

        return $this->createMethodCall($staticCall, 'getCopyrightNotice');
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Migrate the method BackendUtility::TYPO3_copyRightNotice() to use Typo3Information API',
            [
                new CodeSample(
                    <<<'PHP'
$copyright = BackendUtility::TYPO3_copyRightNotice();
PHP
                    ,
                    <<<'PHP'
$copyright = GeneralUtility::makeInstance(Typo3Information::class)->getCopyrightNotice();
PHP
                ),
            ]
        );
    }
}
