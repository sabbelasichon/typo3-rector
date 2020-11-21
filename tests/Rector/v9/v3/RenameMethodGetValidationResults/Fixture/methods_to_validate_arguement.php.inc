<?php

use \TYPO3\CMS\Extbase\Mvc\Controller\Argument;
use \TYPO3\CMS\Extbase\Mvc\Controller\Arguments;

final class RenameMethodGetValidationResultsToValidate
{
    public function method(): void
    {
        Argument::getValidationResults();
        Arguments::getValidationResults();
    }
}

?>
-----
<?php

use \TYPO3\CMS\Extbase\Mvc\Controller\Argument;
use \TYPO3\CMS\Extbase\Mvc\Controller\Arguments;

final class RenameMethodGetValidationResultsToValidate
{
    public function method(): void
    {
        Argument::validate();
        Arguments::validate();
    }
}

?>
