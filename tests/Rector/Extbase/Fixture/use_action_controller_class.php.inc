<?php

use TYPO3\CMS\Extbase\Mvc\Controller\AbstractController;

class MyCustomController extends AbstractController
{

}

?>
-----
<?php

use TYPO3\CMS\Extbase\Mvc\Controller\AbstractController;

class MyCustomController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

}

?>
