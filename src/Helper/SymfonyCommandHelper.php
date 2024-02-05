<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use Rector\Symfony\DataProvider\ServiceMapProvider;
use Rector\Symfony\ValueObject\ServiceDefinition;

final class SymfonyCommandHelper
{
    /**
     * @readonly
     */
    private ServiceMapProvider $serviceMapProvider;

    private string $commandTagName = 'console.command';

    public function __construct(ServiceMapProvider $serviceMapProvider)
    {
        $this->serviceMapProvider = $serviceMapProvider;
    }

    /**
     * @return array<string, mixed>
     */
    public function extractOptionsFromServiceDefinition(ServiceDefinition $serviceDefinition): array
    {
        $options = [];
        foreach ($serviceDefinition->getTags() as $tag) {
            if ($this->commandTagName === $tag->getName()) {
                $options = $tag->getData();
            }
        }

        return $options;
    }

    /**
     * @return ServiceDefinition[]
     */
    public function getCommandsFromServices(): array
    {
        $serviceMap = $this->serviceMapProvider->provide();
        return $serviceMap->getServicesByTag($this->commandTagName);
    }
}
