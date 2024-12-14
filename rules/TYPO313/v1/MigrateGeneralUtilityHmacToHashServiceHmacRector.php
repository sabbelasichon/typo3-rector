<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v1;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.1/Deprecation-102762-GeneralUtilityhmac.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v1\MigrateGeneralUtilityHmacToHashServiceHmacRector\MigrateGeneralUtilityHmacToHashServiceHmacRectorTest
 */
final class MigrateGeneralUtilityHmacToHashServiceHmacRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate GeneralUtility::hmac to HashService::hmac', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;

$hmac = GeneralUtility::hmac('some-input', 'some-secret');
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Crypto\HashService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$hmac = GeneralUtility::makeInstance(HashService::class)->hmac('some-input', 'some-secret');
CODE_SAMPLE
        )]);
    }

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
        if ($this->shouldSkip($node)) {
            return null;
        }

        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall('TYPO3\CMS\Core\Utility\GeneralUtility', 'makeInstance', [
                $this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\Crypto\HashService'),
            ]),
            'hmac',
            $node->getArgs()
        );
    }

    private function shouldSkip(StaticCall $staticCall): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticCall,
            new ObjectType('TYPO3\CMS\Core\Utility\GeneralUtility')
        )) {
            return true;
        }

        return ! $this->isName($staticCall->name, 'hmac');
    }
}
