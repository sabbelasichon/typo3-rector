<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85557-PageRepository-getRootLine.html
 */
final class UseRootlineUtilityInsteadOfGetRootlineMethodRector extends AbstractRector
{
    /**
     * @var Typo3NodeResolver
     */
    private $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

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

        if (! $this->isName($node->name, 'getRootLine')) {
            return null;
        }

        $mountPointParameter = $node->args[1] ?? $this->createArg('');
        return $this->createMethodCall(
            $this->createStaticCall(
                GeneralUtility::class,
                'makeInstance',
                [$this->createClassConstantReference(RootlineUtility::class), $node->args[0], $mountPointParameter]
            ),
            'get'
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use class RootlineUtility instead of method getRootLine', [
            new CodeSample(<<<'PHP'
$rootline = $GLOBALS['TSFE']->sys_page->getRootLine(1);
PHP
                , <<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;
$rootline = GeneralUtility::makeInstance(RootlineUtility::class, 1)->get();
PHP
            ),
        ]);
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if ($this->isMethodStaticCallOrClassMethodObjectType($node, PageRepository::class)) {
            return false;
        }

        if ($this->isObjectType($node->var, 'PageRepository')) {
            return false;
        }
        return ! $this->typo3NodeResolver->isMethodCallOnPropertyOfGlobals(
            $node,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER,
            'sys_page'
        );
    }
}
