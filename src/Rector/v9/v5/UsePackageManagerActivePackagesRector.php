<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v5;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.5/Deprecation-86404-GLOBALSTYPO3_LOADED_EXT.html
 */
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
<<<<<<< HEAD
<<<<<<< HEAD
     * @return array<class-string<Node>>
=======
=======
>>>>>>> da7142f... rename AbstractCommunityRectorTestCase to AbstractRectorTestCase
     * @return array<class-string<\PhpParser\Node>>
>>>>>>> f7cbd4b... make PHPStan smarted on getNodeTypes()
     */

    /**
=======
>>>>>>> 8781ff4... rename AbstractCommunityRectorTestCase to AbstractRectorTestCase
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [ArrayDimFetch::class];
    }

    /**
     * @param ArrayDimFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->typo3NodeResolver->isTypo3Global($node, Typo3NodeResolver::TYPO3_LOADED_EXT)) {
            return $this->nodeFactory->createMethodCall(
                $this->nodeFactory->createStaticCall(
                    GeneralUtility::class,
                    'makeInstance',
                    [$this->nodeFactory->createClassConstReference(PackageManager::class)]
                ),
                'getActivePackages'
            );
        }
        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use PackageManager API instead of $GLOBALS[\'TYPO3_LOADED_EXT\']', [
            new CodeSample(<<<'CODE_SAMPLE'
$extensionList = $GLOBALS['TYPO3_LOADED_EXT'];
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$extensionList = GeneralUtility::makeInstance(PackageManager::class)->getActivePackages();
CODE_SAMPLE
),
        ]);
    }
}
