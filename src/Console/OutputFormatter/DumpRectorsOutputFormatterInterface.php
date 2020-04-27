<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Console\OutputFormatter;

use Rector\Core\Contract\Rector\RectorInterface;

interface DumpRectorsOutputFormatterInterface
{
    public function getName(): string;

    /**
     * @param RectorInterface[] $genericRectors
     */
    public function format(array $genericRectors): void;
}
