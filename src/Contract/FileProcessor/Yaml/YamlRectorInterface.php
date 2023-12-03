<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Contract\FileProcessor\Yaml;

use Rector\Core\Contract\Rector\RectorInterface;

interface YamlRectorInterface extends RectorInterface
{
    /**
     * @param mixed[] $yaml
     * @return mixed[]
     */
    public function refactor(array $yaml): array;
}
