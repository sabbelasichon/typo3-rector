<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use PhpParser\Node\Expr\ClassConstFetch;
use Rector\Core\PhpParser\Node\NodeFactory;

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
            return $this->nodeFactory->createClassConstFetch('TYPO3\CMS\Core\Log\LogLevel', 'INFO');
        }

        if ($severityValue === 1) {
            return $this->nodeFactory->createClassConstFetch('TYPO3\CMS\Core\Log\LogLevel', 'NOTICE');
        }

        if ($severityValue === 2) {
            return $this->nodeFactory->createClassConstFetch('TYPO3\CMS\Core\Log\LogLevel', 'WARNING');
        }

        if ($severityValue === 3) {
            return $this->nodeFactory->createClassConstFetch('TYPO3\CMS\Core\Log\LogLevel', 'ERROR');
        }

        if ($severityValue === 4) {
            return $this->nodeFactory->createClassConstFetch('TYPO3\CMS\Core\Log\LogLevel', 'CRITICAL');
        }

        return $this->nodeFactory->createClassConstFetch('TYPO3\CMS\Core\Log\LogLevel', 'INFO');
    }
}
