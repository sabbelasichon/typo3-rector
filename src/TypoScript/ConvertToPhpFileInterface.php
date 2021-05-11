<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript;

use Rector\Core\Contract\Rector\RectorInterface;
use Ssch\TYPO3Rector\ValueObject\TypoScriptToPhpFile;

interface ConvertToPhpFileInterface extends RectorInterface
{
    public function convert(): ?TypoScriptToPhpFile;

    public function getMessage(): string;
}
