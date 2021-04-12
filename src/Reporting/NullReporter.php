<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Reporting;

use Ssch\TYPO3Rector\Reporting\ValueObject\Report;

final class NullReporter implements Reporter
{
    public function report(Report $report): void
    {
    }
}
