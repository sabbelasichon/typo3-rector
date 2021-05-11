<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript;

use Rector\Core\Contract\Rector\RectorInterface;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;

interface ConvertToPhpFileInterface extends RectorInterface
{
    public function convert(): ?AddedFileWithContent;

    public function getMessage(): string;
}
