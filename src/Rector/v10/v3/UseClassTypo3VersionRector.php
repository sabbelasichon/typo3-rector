<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.3/Deprecation-90007-GlobalConstantsTYPO3_versionAndTYPO3_branch.html
 */
final class UseClassTypo3VersionRector extends AbstractRector
{
    /**
     * @var array
     */
    private const CONSTANTS_TO_REFACTOR = ['TYPO3_version', 'TYPO3_branch'];

    public function getNodeTypes(): array
    {
        return [ConstFetch::class];
    }

    /**
     * @param ConstFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isNames($node->name, self::CONSTANTS_TO_REFACTOR)) {
            return null;
        }

        $methodCall = $this->isName($node->name, 'TYPO3_version') ? 'getVersion' : 'getBranch';

        return $this->createMethodCall($this->createStaticCall(GeneralUtility::class, 'makeInstance', [
            $this->createClassConstantReference(Typo3Version::class),
        ]), $methodCall);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Use class Typo3Version instead of the constants',
            [
                new CodeSample(
                    <<<'PHP'
$typo3Version = TYPO3_version;
$typo3Branch = TYPO3_branch;
PHP
                    ,
                    <<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Information\Typo3Version;
$typo3Version = GeneralUtility::makeInstance(Typo3Version::class)->getVersion();
$typo3Branch = GeneralUtility::makeInstance(Typo3Version::class)->getBranch();
PHP
                ),
            ]
        );
    }
}
