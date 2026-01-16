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

    /**
     * Check if a service is public based on its class name
     */
    public function isPublicService(string $className): bool
    {
        $definition = $this->getServiceDefinitionByClassName($className);

        return $definition instanceof ServiceDefinition && $definition->isPublic();
    }

    /**
     * Checks if the service is explicitly marked as non-shared
     */
    public function isNotSharedService(string $className): bool
    {
        $definition = $this->getServiceDefinitionByClassName($className);

        return $definition instanceof ServiceDefinition && ! $definition->isShared();
    }

    public function getServiceDefinitionByClassName(string $className): ?ServiceDefinition
    {
        $serviceMap = $this->serviceMapProvider->provide();
        $services = $serviceMap->getServices();

        if (isset($services[$className])) {
            return $services[$className];
        }

        foreach ($services as $serviceDefinition) {
            if ($serviceDefinition->getClass() === $className) {
                return $serviceDefinition;
            }
        }

        return null;
    }
}
