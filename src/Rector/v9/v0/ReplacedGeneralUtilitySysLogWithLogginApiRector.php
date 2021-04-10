<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\MagicConst\Class_;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\OldSeverityToLogLevelMapper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Breaking-82430-ReplacedGeneralUtilitysysLogWithLoggingAPI.html
 */
final class ReplacedGeneralUtilitySysLogWithLogginApiRector extends AbstractRector
{
    /**
     * @var OldSeverityToLogLevelMapper
     */
    private $oldSeverityToLogLevelMapper;

    public function __construct(OldSeverityToLogLevelMapper $oldSeverityToLogLevelMapper)
    {
        $this->oldSeverityToLogLevelMapper = $oldSeverityToLogLevelMapper;
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
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(GeneralUtility::class)
        )) {
            return null;
        }

        if (! $this->isNames($node->name, ['initSysLog', 'sysLog'])) {
            return null;
        }

        if ($this->isName($node->name, 'initSysLog')) {
            $this->removeNode($node);
            return null;
        }

        $makeInstanceCall = $this->nodeFactory->createStaticCall(GeneralUtility::class, 'makeInstance', [
            $this->nodeFactory->createClassConstReference(LogManager::class),
        ]
        );

        $loggerCall = $this->nodeFactory->createMethodCall($makeInstanceCall, 'getLogger', [new Class_()]);

        $args = [];

        $severity = $this->nodeFactory->createClassConstFetch(LogLevel::class, 'INFO');

        if (isset($node->args[2]) && $severityValue = $this->valueResolver->getValue($node->args[2]->value)) {
            $severity = $this->oldSeverityToLogLevelMapper->mapSeverityToLogLevel($severityValue);
        }
        $args[] = $severity;

        $args[] = $node->args[0] ?? $this->nodeFactory->createArg(new String_(''));

        return $this->nodeFactory->createMethodCall($loggerCall, 'log', $args);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replaced GeneralUtility::sysLog with Logging API', [new CodeSample(<<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
GeneralUtility::initSysLog();
GeneralUtility::sysLog('message', 'foo', 0);
CODE_SAMPLE
        , <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Utility\GeneralUtility;
GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__)->log(LogLevel::INFO, 'message');
CODE_SAMPLE
        )]);
    }
}
