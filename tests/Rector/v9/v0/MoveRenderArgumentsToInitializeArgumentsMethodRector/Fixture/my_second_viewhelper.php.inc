<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Fluid\ViewHelpers\Fixture;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class InArrayViewHelper extends AbstractViewHelper
{
    /**
     * @param mixed $valueToCheckAgainst
     *
     * @return bool
     */
    public function render($valueToCheckAgainst, array $arrayToCheckAgainst)
    {
        foreach ($arrayToCheckAgainst as $item) {
            if ($item === $valueToCheckAgainst) {
                return true;
            }
        }

        return false;
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Fluid\ViewHelpers\Fixture;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class InArrayViewHelper extends AbstractViewHelper
{
    /**
     * @return bool
     */
    public function render()
    {
        $valueToCheckAgainst = $this->arguments['valueToCheckAgainst'];
        $arrayToCheckAgainst = $this->arguments['arrayToCheckAgainst'];
        foreach ($arrayToCheckAgainst as $item) {
            if ($item === $valueToCheckAgainst) {
                return true;
            }
        }
        return false;
    }

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('valueToCheckAgainst', 'mixed', '', true);
        $this->registerArgument('arrayToCheckAgainst', 'array', '', true);
    }
}

?>
