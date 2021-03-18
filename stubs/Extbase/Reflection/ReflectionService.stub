<?php
declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Reflection;

if (class_exists(ReflectionService::class)) {
    return;
}

final class ReflectionService
{
    public function getClassTagsValues($className): array
    {
        $classSchema = $this->getClassSchema($className);

        return $classSchema->getTags();
    }

    public function getClassTagValues($className, $tag): array
    {
        $classSchema = $this->getClassSchema($className);

        return $classSchema->getTags()[$tag] ?? [];
    }

    public function getClassPropertyNames($className): array
    {
        $classSchema = $this->getClassSchema($className);

        return array_keys($classSchema->getProperties());
    }

    public function getClassSchema($classNameOrObject): ClassSchema
    {
        return new ClassSchema();
    }

    public function hasMethod($className, $methodName): bool
    {
        $classSchema = $this->getClassSchema($className);

        return $classSchema->hasMethod($methodName);
    }

    public function getMethodTagsValues($className, $methodName): array
    {
        return $this->getClassSchema($className)->getMethod($methodName)['tags'] ?? [];
    }

    public function getMethodParameters($className, $methodName): array
    {
        $classSchema = $this->getClassSchema($className);

        return $classSchema->getMethod($methodName)['params'] ?? [];
    }

    public function getPropertyTagsValues($className, $propertyName): array
    {
        $classSchema = $this->getClassSchema($className);

        return $classSchema->getProperty($propertyName)['tags'] ?? [];
    }

    public function getPropertyTagValues($className, $propertyName, $tag): array
    {
        $classSchema = $this->getClassSchema($className);

        return $classSchema->getProperty($propertyName)['tags'][$tag] ?? [];
    }

    public function isClassTaggedWith($className, $tag): bool
    {
        $classSchema = $this->getClassSchema($className);

        return (bool)count(array_filter(array_keys($classSchema->getTags()), static function ($tagName) use ($tag) {
            return $tagName === $tag;
        }));
    }

    public function isPropertyTaggedWith($className, $propertyName, $tag): bool
    {
        $classSchema = $this->getClassSchema($className);
        $property = $classSchema->getProperty($propertyName);
        return empty($property) ? false : isset($property['tags'][$tag]);
    }
}
