<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript;

use Ssch\TYPO3Rector\ValueObject\TypoScriptToPhpFile;

interface ConvertToPhpFileInterface
{
    public function convert(): TypoScriptToPhpFile;
}
