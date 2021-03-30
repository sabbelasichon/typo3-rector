<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Processor;

interface ConfigurableProcessorInterface
{
    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void;
}
