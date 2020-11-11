<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v7\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Extbase\Service\TypeHandlingService;
use TYPO3\CMS\Extbase\Utility\TypeHandlingUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/7.0/Breaking-61786-ExtbaseDeprecatedTypeHandlingServiceRemoved.html
 */
final class TypeHandlingServiceToTypeHandlingUtilityRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, TypeHandlingService::class)) {
            return null;
        }

        $methodCall = $this->getName($node->name);

        if (null === $methodCall) {
            return null;
        }

        return $this->createStaticCall(TypeHandlingUtility::class, $methodCall, $node->args);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use TypeHandlingUtility instead of TypeHandlingService', [
            new CodeSample(<<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\TypeHandlingService;
GeneralUtility::makeInstance(TypeHandlingService::class)->isSimpleType('string');
PHP
                , <<<'PHP'
use TYPO3\CMS\Extbase\Utility\TypeHandlingUtility;
TypeHandlingUtility::isSimpleType('string');
PHP
            ),
        ]);
    }
}
