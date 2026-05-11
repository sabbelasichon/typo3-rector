<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MoveSchedulerFrequencyOptionsToTCARector\Fixture;

// Append-syntax assignments must not crash the rule (dim is null for [] on the LHS)
$data[][] = 'value';
$nested[][] = 'something';