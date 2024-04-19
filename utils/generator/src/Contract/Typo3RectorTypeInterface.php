<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\Contract;

use Stringable;

interface Typo3RectorTypeInterface extends Stringable
{
    public function getRectorClass(): string;

    public function getRectorShortClassName(): string;

    public function getRectorBodyTemplate(): string;
}
