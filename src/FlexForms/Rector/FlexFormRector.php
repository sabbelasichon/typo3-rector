<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FlexForms\Rector;

use DOMDocument;
use Rector\Core\Contract\Rector\RectorInterface;

interface FlexFormRector extends RectorInterface
{
    public function transform(DOMDocument $domDocument): bool;
}
