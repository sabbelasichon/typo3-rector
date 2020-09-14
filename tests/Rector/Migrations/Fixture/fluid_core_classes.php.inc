<?php

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

final class MySpecialViewHelper extends AbstractViewHelper
{

}

?>
-----
<?php

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class MySpecialViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{

}

?>
