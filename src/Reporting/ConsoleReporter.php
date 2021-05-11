<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Reporting;

use Rector\Core\Console\Output\RectorOutputStyle;
use Ssch\TYPO3Rector\Reporting\ValueObject\Report;

final class ConsoleReporter implements Reporter
{
    /**
     * @var RectorOutputStyle
     */
    private $rectorOutputStyle;

    public function __construct(RectorOutputStyle $rectorOutputStyle)
    {
        $this->rectorOutputStyle = $rectorOutputStyle;
    }

    public function report(Report $report): void
    {
        $this->rectorOutputStyle->warning($report->getMessage());
    }
}
