<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript;

use Helmich\TypoScriptParser\Parser\Traverser\Visitor;
use Rector\Core\Contract\Rector\RectorInterface;

interface TypoScriptRectorInterface extends Visitor, RectorInterface
{
}
