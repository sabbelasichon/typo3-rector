<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseClassSchemaInsteadReflectionServiceMethods\Fixture;

use stdClass;
use TYPO3\CMS\Extbase\Reflection\ReflectionService;

class MyClassService
{

    /**
     * @var ReflectionService
     * @inject
     */
    protected $reflectionService;

    public function init(): void
    {
        $classTagValues = $this->reflectionService->getClassTagValues(stdClass::class, 'tag');
        $classTagsValues = $this->reflectionService->getClassTagsValues(stdClass::class);
        if ($this->reflectionService->isClassTaggedWith(stdClass::class, 'tag')) {

        }
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseClassSchemaInsteadReflectionServiceMethods\Fixture;

use stdClass;
use TYPO3\CMS\Extbase\Reflection\ReflectionService;

class MyClassService
{

    /**
     * @var ReflectionService
     * @inject
     */
    protected $reflectionService;

    public function init(): void
    {
        $classTagValues = $this->reflectionService->getClassSchema(stdClass::class)->getTags()['tag'] ?? [];
        $classTagsValues = $this->reflectionService->getClassSchema(stdClass::class)->getTags();
        $tag = 'tag';
        if ((bool) count(array_filter(array_keys($this->reflectionService->getClassSchema(stdClass::class)->getTags()), function ($tagName) use ($tag) {
            return $tagName === $tag;
        }))) {

        }
    }
}

?>
