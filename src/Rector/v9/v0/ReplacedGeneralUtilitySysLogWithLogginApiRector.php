<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\MagicConst\Class_;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeTraverser;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\OldSeverityToLogLevelMapper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.0/Breaking-82430-ReplacedGeneralUtilitysysLogWithLoggingAPI.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v0\ReplacedGeneralUtilitySysLogWithLogginApiRector\ReplacedGeneralUtilitySysLogWithLogginApiRectorTest
 */
final class ReplacedGeneralUtilitySysLogWithLogginApiRector extends AbstractRector
{
    /**
     * @readonly
     */
    private OldSeverityToLogLevelMapper $oldSeverityToLogLevelMapper;

    public function __construct(OldSeverityToLogLevelMapper $oldSeverityToLogLevelMapper)
    {
        $this->oldSeverityToLogLevelMapper = $oldSeverityToLogLevelMapper;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Stmt\Expression::class];
    }

    /**
     * @param Node\Stmt\Expression $node
     * @return int|null|Node
     */
    public function refactor(Node $node)
    {
        $staticCall = $node->expr;

        if (! $staticCall instanceof StaticCall) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticCall,
            new ObjectType('TYPO3\CMS\Core\Utility\GeneralUtility')
        )) {
            return null;
        }

        if (! $this->isNames($staticCall->name, ['initSysLog', 'sysLog'])) {
            return null;
        }

        if ($this->isName($staticCall->name, 'initSysLog')) {
            return NodeTraverser::REMOVE_NODE;
        }

        $makeInstanceCall = $this->nodeFactory->createStaticCall(
            'TYPO3\CMS\Core\Utility\GeneralUtility',
            'makeInstance',
            [$this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\Log\LogManager')]
        );

        $loggerCall = $this->nodeFactory->createMethodCall($makeInstanceCall, 'getLogger', [new Class_()]);

        $args = [];

        $severity = $this->nodeFactory->createClassConstFetch('TYPO3\CMS\Core\Log\LogLevel', 'INFO');

        if (isset($staticCall->args[2]) && $severityValue = $this->valueResolver->getValue(
            $staticCall->args[2]->value
        )) {
            $severity = $this->oldSeverityToLogLevelMapper->mapSeverityToLogLevel((int) $severityValue);
        }

        $args[] = $severity;

        $args[] = $staticCall->args[0] ?? $this->nodeFactory->createArg(new String_(''));

        $node->expr = $this->nodeFactory->createMethodCall($loggerCall, 'log', $args);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replaced GeneralUtility::sysLog with Logging API', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
GeneralUtility::initSysLog();
GeneralUtility::sysLog('message', 'foo', 0);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Utility\GeneralUtility;
GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__)->log(LogLevel::INFO, 'message');
CODE_SAMPLE
        )]);
    }
}
