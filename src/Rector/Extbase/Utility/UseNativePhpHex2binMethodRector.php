<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Extbase\Utility;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Extbase\Utility\TypeHandlingUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Deprecation-87613-DeprecateTYPO3CMSExtbaseUtilityTypeHandlingUtilityhex2bin.html
 */
final class UseNativePhpHex2binMethodRector extends AbstractRector
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
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, TypeHandlingUtility::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'hex2bin')) {
            return null;
        }

        return $this->createFuncCall('hex2bin', $node->args);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Turns \TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin calls to native php hex2bin',
            [
                new CodeSample(
                    '\TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin("6578616d706c65206865782064617461");',
                    'hex2bin("6578616d706c65206865782064617461");'
                ),
            ]
        );
    }
}
