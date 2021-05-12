<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Yaml\Form\Transformer;

use Rector\Core\Contract\Rector\RectorInterface;

interface FormYamlRector extends RectorInterface
{
    public function transform(array $yaml): array;
}
