<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseClassSchemaInsteadReflectionServiceMethodsRector\Fixture;

use stdClass;
use TYPO3\CMS\Extbase\Reflection\ReflectionService;

class MyMethodService
{

    /**
     * @var ReflectionService
     * @inject
     */
    protected $reflectionService;

    public function init(): void
    {
        if ($this->reflectionService->hasMethod(stdClass::class, 'foo')) {
            // Do something
        }

        $methodTagsValues = $this->reflectionService->getMethodTagsValues(stdClass::class, 'foo');
        $methodParameters = $this->reflectionService->getMethodParameters(stdClass::class, 'foo');
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseClassSchemaInsteadReflectionServiceMethodsRector\Fixture;

use stdClass;
use TYPO3\CMS\Extbase\Reflection\ReflectionService;

class MyMethodService
{

    /**
     * @var ReflectionService
     * @inject
     */
    protected $reflectionService;

    public function init(): void
    {
        if ($this->reflectionService->getClassSchema(stdClass::class)->hasMethod('foo')) {
            // Do something
        }

        $methodTagsValues = $this->reflectionService->getClassSchema(stdClass::class)->getMethod('foo')['tags'] ?? [];
        $methodParameters = $this->reflectionService->getClassSchema(stdClass::class)->getMethod('foo')['params'] ?? [];
    }
}

?>
