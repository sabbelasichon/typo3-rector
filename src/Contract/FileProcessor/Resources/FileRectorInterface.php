<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Contract\FileProcessor\Resources;

use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\ValueObject\Application\File;

interface FileRectorInterface extends RectorInterface
{
    public function refactorFile(File $file): void;
}
