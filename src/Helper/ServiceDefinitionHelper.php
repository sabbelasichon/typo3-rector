<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use Rector\Symfony\DataProvider\ServiceMapProvider;
use Rector\Symfony\ValueObject\ServiceDefinition;

final class ServiceDefinitionHelper
{
    /**
     * @readonly
     */
    private ServiceMapProvider $serviceMapProvider;

    public function __construct(ServiceMapProvider $serviceMapProvider)
    {
        $this->serviceMapProvider = $serviceMapProvider;
    }

    /**
     * @return array<string, mixed>
     */
    public function extractOptionsFromServiceDefinition(ServiceDefinition $serviceDefinition, string $tagName): array
    {
        $options = [];
        foreach ($serviceDefinition->getTags() as $tag) {
            if ($tagName === $tag->getName()) {
                $options = $tag->getData();
            }
        }

        return $options;
    }

    /**
     * @return ServiceDefinition[]
     */
    public function getServiceDefinitionsByTagName(string $tagName): array
    {
        $serviceMap = $this->serviceMapProvider->provide();
        return $serviceMap->getServicesByTag($tagName);
    }
}
