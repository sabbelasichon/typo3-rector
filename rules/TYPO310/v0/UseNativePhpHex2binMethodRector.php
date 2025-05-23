<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO310\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.0/Deprecation-87613-DeprecateTYPO3CMSExtbaseUtilityTypeHandlingUtilityhex2bin.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v0\UseNativePhpHex2binMethodRector\UseNativePhpHex2binMethodRectorTest
 */
final class UseNativePhpHex2binMethodRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @return array<class-string<Node>>
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
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Extbase\Utility\TypeHandlingUtility')
        )) {
            return null;
        }

        if (! $this->isName($node->name, 'hex2bin')) {
            return null;
        }

        return $this->nodeFactory->createFuncCall('hex2bin', $node->args);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Turn `TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin` calls to native php `hex2bin()`',
            [
                new CodeSample(
                    'TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin("6578616d706c65206865782064617461");',
                    'hex2bin("6578616d706c65206865782064617461");'
                ),
            ]
        );
    }
}
