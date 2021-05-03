<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Reporting;

use Ssch\TYPO3Rector\Reporting\ValueObject\Report;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ConsoleReporter implements Reporter
{
    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    public function __construct(SymfonyStyle $symfonyStyle)
    {
        $this->symfonyStyle = $symfonyStyle;
    }

    public function report(Report $report): void
    {
        $this->symfonyStyle->caution($report->getMessage());
    }
}
