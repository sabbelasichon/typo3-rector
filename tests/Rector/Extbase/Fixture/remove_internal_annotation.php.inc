<?php

use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * @internal
 */
final class TestCommandControllerWithInternalAnnotation extends CommandController
{
}

?>
-----
<?php

use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

final class TestCommandControllerWithInternalAnnotation extends CommandController
{
}

?>
