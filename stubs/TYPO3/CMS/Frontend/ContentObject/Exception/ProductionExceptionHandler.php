<?php
declare(strict_types=1);


namespace TYPO3\CMS\Frontend\ContentObject\Exception;

use TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;

if (class_exists('TYPO3\CMS\Frontend\ContentObject\ProductionExceptionHandler')) {
    return;
}

class ProductionExceptionHandler implements ExceptionHandlerInterface
{
    protected array $configuration = [];

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function handle(\Exception $exception, AbstractContentObject $contentObject = null, $contentObjectConfiguration = []): void
    {
        // TODO: Implement handle() method.
    }
}
