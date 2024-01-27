<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use PhpParser\Node\Expr\ClassConstFetch;
use Rector\PhpParser\Node\NodeFactory;
use TYPO3\CMS\Core\Log\LogLevel;

final class OldSeverityToLogLevelMapper
{
    /**
     * @readonly
     */
    private NodeFactory $nodeFactory;

    public function __construct(NodeFactory $nodeFactory)
    {
        $this->nodeFactory = $nodeFactory;
    }

    public function mapSeverityToLogLevel(int $severityValue): ClassConstFetch
    {
        if ($severityValue === 0) {
            return $this->nodeFactory->createClassConstFetch(LogLevel::class, 'INFO');
        }

        if ($severityValue === 1) {
            return $this->nodeFactory->createClassConstFetch(LogLevel::class, 'NOTICE');
        }

        if ($severityValue === 2) {
            return $this->nodeFactory->createClassConstFetch(LogLevel::class, 'WARNING');
        }

        if ($severityValue === 3) {
            return $this->nodeFactory->createClassConstFetch(LogLevel::class, 'ERROR');
        }

        if ($severityValue === 4) {
            return $this->nodeFactory->createClassConstFetch(LogLevel::class, 'CRITICAL');
        }

        return $this->nodeFactory->createClassConstFetch(LogLevel::class, 'INFO');
    }
}
