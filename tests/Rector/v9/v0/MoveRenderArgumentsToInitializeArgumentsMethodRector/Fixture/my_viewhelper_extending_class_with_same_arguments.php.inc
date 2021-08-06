<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Fluid\ViewHelpers\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v9\v0\MoveRenderArgumentsToInitializeArgumentsMethodRector\Source\MySpecialAbstractViewHelper;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

final class MyOldViewHelperWithAlreadyDefinedArguments extends MySpecialAbstractViewHelper
{
    /**
     * @param array $strings An array of strings
     * @param \Traversable|iterable $something Something
     * @param \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $object Object
     * @param string $as The name to access the variable in the template
     * @param string $delimiter The delimiter to explode the strings
     * @return string
     */
    public function render(array $strings, $something, QueryResultInterface $object, string $as = 'items', $delimiter = '#'): string
    {
        return sprintf('%s-%s-%s', $strings, $as, $delimiter);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Fluid\ViewHelpers\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v9\v0\MoveRenderArgumentsToInitializeArgumentsMethodRector\Source\MySpecialAbstractViewHelper;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

final class MyOldViewHelperWithAlreadyDefinedArguments extends MySpecialAbstractViewHelper
{
    /**
     * @return string
     */
    public function render(): string
    {
        $strings = $this->arguments['strings'];
        $something = $this->arguments['something'];
        $object = $this->arguments['object'];
        $as = $this->arguments['as'];
        $delimiter = $this->arguments['delimiter'];
        return sprintf('%s-%s-%s', $strings, $as, $delimiter);
    }

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('strings', 'array', 'An array of strings', true);
        $this->registerArgument('something', 'iterable', 'Something', true);
        $this->registerArgument('object', QueryResultInterface::class, 'Object', true);
    }
}

?>
