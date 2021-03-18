<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\MagicConst\Class_;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Deprecation-52694-DeprecatedGeneralUtilitydevLog.html
 */
final class SubstituteGeneralUtilityDevLogRector extends AbstractRector
{
    /**
     * @var string
     */
    private const INFO = 'INFO';

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
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, GeneralUtility::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'devLog')) {
            return null;
        }

        $makeInstanceCall = $this->nodeFactory->createStaticCall(GeneralUtility::class, 'makeInstance', [
            $this->nodeFactory->createClassConstReference(LogManager::class),
        ]
        );

        $loggerCall = $this->nodeFactory->createMethodCall($makeInstanceCall, 'getLogger', [new Class_()]);

        $args = [];

        $severity = $this->nodeFactory->createClassConstFetch(LogLevel::class, self::INFO);

        if (isset($node->args[2]) && $severityValue = $this->valueResolver->getValue($node->args[2]->value)) {
            $severity = $this->mapSeverityToLogLevel($severityValue);
        }
        $args[] = $severity;

        $args[] = $node->args[0] ?? $this->nodeFactory->createArg(new String_(''));
        $args[] = $node->args[3] ?? $this->nodeFactory->createArg(new String_(''));

        return $this->nodeFactory->createMethodCall($loggerCall, 'log', $args);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Substitute GeneralUtility::devLog() to Logging API', [
            new CodeSample(<<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
GeneralUtility::devLog('message', 'foo', 0, $data);
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__)->log(LogLevel::INFO, 'message', $data);
CODE_SAMPLE
            ),
        ]);
    }

    private function mapSeverityToLogLevel(int $severityValue): ClassConstFetch
    {
        if (0 === $severityValue) {
            return $this->nodeFactory->createClassConstFetch(LogLevel::class, self::INFO);
        }

        if (1 === $severityValue) {
            return $this->nodeFactory->createClassConstFetch(LogLevel::class, 'NOTICE');
        }

        if (2 === $severityValue) {
            return $this->nodeFactory->createClassConstFetch(LogLevel::class, 'WARNING');
        }

        if (3 === $severityValue) {
            return $this->nodeFactory->createClassConstFetch(LogLevel::class, 'ERROR');
        }

        if (4 === $severityValue) {
            return $this->nodeFactory->createClassConstFetch(LogLevel::class, 'CRITICAL');
        }

        return $this->nodeFactory->createClassConstFetch(LogLevel::class, self::INFO);
    }
}
