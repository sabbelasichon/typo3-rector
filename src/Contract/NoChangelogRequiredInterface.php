<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Contract;

/**
 * Marker interface for rules where no direct changelog exists for and our custom PHPStan rule AddChangelogDocBlockForRectorClassRule should be ignored
 */
interface NoChangelogRequiredInterface
{
}
