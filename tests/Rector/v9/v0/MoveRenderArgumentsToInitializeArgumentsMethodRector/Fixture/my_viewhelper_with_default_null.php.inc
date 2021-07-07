<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Fluid\ViewHelpers\Fixture;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class LegacyViewHelper extends AbstractViewHelper
{
    /**
     * @param mixed $value The value to output
     * @return string
     */
    public function render($value = null)
    {
        if (null === $value) {
            $value = $this->renderChildren();
        }

        // remove new line - does not matter in html anyway
        $value = str_replace(chr(10), '', $value);
        // remove multiple whitespaces
        $value = preg_replace('#\s+#', ' ', $value);
        return trim($value);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Fluid\ViewHelpers\Fixture;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class LegacyViewHelper extends AbstractViewHelper
{
    /**
     * @return string
     */
    public function render()
    {
        $value = $this->arguments['value'];
        if (null === $value) {
            $value = $this->renderChildren();
        }
        // remove new line - does not matter in html anyway
        $value = str_replace(chr(10), '', $value);
        // remove multiple whitespaces
        $value = preg_replace('#\s+#', ' ', $value);
        return trim($value);
    }

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('value', 'mixed', 'The value to output', false);
    }
}

?>
