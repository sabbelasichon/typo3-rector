<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseClassSchemaInsteadReflectionServiceMethods\Fixture;

use TYPO3\CMS\Extbase\Reflection\ReflectionService;

class MyService
{

    /**
     * @var ReflectionService
     * @inject
     */
    protected $reflectionService;

    public function init(): void
    {
        $properties = $this->reflectionService->getClassPropertyNames(\stdClass::class);
        foreach ($properties as $property) {
            $tags = $this->reflectionService->getPropertyTagsValues(\stdClass::class, $property);
            $tag = $this->reflectionService->getPropertyTagValues(\stdClass::class, $property, 'tag');
        }
    }

    public function fooBarBaz()
    {
        if ( ! empty($this->reflectionService->getPropertyTagsValues(\stdClass::class, 'property'))) {
            // Do something here
        }
    }

    public function getProperty($property)
    {
        return $this->reflectionService->getPropertyTagsValues(\stdClass::class, $property);
    }

    public function isPropertyTaggedWith($property)
    {
        if($this->reflectionService->isPropertyTaggedWith(\stdClass::class, $property, 'tag')) {
            // Do something here
        }
        return $this->reflectionService->isPropertyTaggedWith(\stdClass::class, $property, 'tag');
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseClassSchemaInsteadReflectionServiceMethods\Fixture;

use stdClass;
use TYPO3\CMS\Extbase\Reflection\ReflectionService;

class MyService
{

    /**
     * @var ReflectionService
     * @inject
     */
    protected $reflectionService;

    public function init(): void
    {
        $properties = array_keys($this->reflectionService->getClassSchema(stdClass::class)->getProperties());
        foreach ($properties as $property) {
            $propertyTagsValues = $this->reflectionService->getClassSchema(stdClass::class)->getProperty($property)['tags'] ?? [];
            $tags = $propertyTagsValues;
            $tag = $this->reflectionService->getClassSchema(stdClass::class)->getProperty($property)['tags']['tag'] ?? [];
        }
    }

    public function fooBarBaz()
    {
        $propertyTagsValues = $this->reflectionService->getClassSchema(stdClass::class)->getProperty('property')['tags'] ?? [];
        if ( ! empty($propertyTagsValues)) {
            // Do something here
        }
    }

    public function getProperty($property)
    {
        $propertyTagsValues = $this->reflectionService->getClassSchema(stdClass::class)->getProperty($property)['tags'] ?? [];
        return $propertyTagsValues;
    }

    public function isPropertyTaggedWith($property)
    {
        $propertyReflectionService = $this->reflectionService->getClassSchema(stdClass::class)->getProperty($property);
        if(empty($propertyReflectionService) ? false : isset($propertyReflectionService['tags']['tag'])) {
            // Do something here
        }
        $propertyReflectionService = $this->reflectionService->getClassSchema(stdClass::class)->getProperty($property);
        return empty($propertyReflectionService) ? false : isset($propertyReflectionService['tags']['tag']);
    }
}

?>
