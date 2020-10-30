<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Fluid\ViewHelpers\Fixture;

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class MyOldViewHelper extends AbstractViewHelper
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

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class MyOldViewHelper extends AbstractViewHelper
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
        $this->registerArgument('something', '\Traversable', 'Something', true);
        $this->registerArgument('object', '\TYPO3\CMS\Extbase\Persistence\QueryResultInterface', 'Object', true);
        $this->registerArgument('as', 'string', 'The name to access the variable in the template', false, 'items');
        $this->registerArgument('delimiter', 'string', 'The delimiter to explode the strings', false, '#');
    }
}

?>
