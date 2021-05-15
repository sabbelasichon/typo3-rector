<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use PhpParser\Node\Expr\ClassConstFetch;
use Rector\Core\PhpParser\Node\NodeFactory;
use TYPO3\CMS\Core\Log\LogLevel;

final class OldSeverityToLogLevelMapper
{
    public function __construct(private NodeFactory $nodeFactory)
    {
    }

    public function mapSeverityToLogLevel(int $severityValue): ClassConstFetch
    {
        if (0 === $severityValue) {
            return $this->nodeFactory->createClassConstFetch(LogLevel::class, 'INFO');
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

        return $this->nodeFactory->createClassConstFetch(LogLevel::class, 'INFO');
    }
}
