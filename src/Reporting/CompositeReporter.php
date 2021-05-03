<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Reporting;

use Ssch\TYPO3Rector\Reporting\ValueObject\Report;
use Webmozart\Assert\Assert;

final class CompositeReporter implements Reporter
{
    /**
     * @var Reporter[]
     */
    private $reporters = [];

    /**
     * @param Reporter[] $reporters
     */
    public function __construct(array $reporters = [])
    {
        Assert::allImplementsInterface($reporters, Reporter::class);
        $this->reporters = $reporters;
    }

    public function addReporter(Reporter $reporter): void
    {
        $this->reporters[] = $reporter;
    }

    public function report(Report $report): void
    {
        foreach ($this->reporters as $reporter) {
            $reporter->report($report);
        }
    }
}
