<?php

final class MySpecialViewHelperWithFullQualifiedNamespace extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

}

?>
-----
<?php

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
final class MySpecialViewHelperWithFullQualifiedNamespace extends AbstractViewHelper
{

}
