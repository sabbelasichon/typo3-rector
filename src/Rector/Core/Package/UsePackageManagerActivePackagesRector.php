<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\Package;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class UsePackageManagerActivePackagesRector extends AbstractRector
{
    /**
     * @var Typo3NodeResolver
     */
    private $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [ArrayDimFetch::class];
    }

    /**
     * @inheritDoc
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->typo3NodeResolver->isTypo3Global($node, Typo3NodeResolver::TYPO3_LOADED_EXT)) {
            return $this->createMethodCall($this->createStaticCall(
                GeneralUtility::class,
                'makeInstance',
                [
                    $this->createClassConstant(PackageManager::class, 'class'),
                ]
            ), 'getActivePackages');
        }

        return $node;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use PackageManager API instead of $GLOBALS[\'TYPO3_LOADED_EXT\']', [
            new CodeSample(
                <<<'PHP'
$extensionList = $GLOBALS['TYPO3_LOADED_EXT'];
PHP
                ,
                <<<'PHP'
$extensionList = GeneralUtility::makeInstance(PackageManager::class)->getActivePackages();
PHP
            ),
        ]);
    }
}
