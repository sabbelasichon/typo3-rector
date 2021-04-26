<?php

use TYPO3\CMS\Extbase\Mvc\Controller\AbstractController;

class MyAbstractController extends AbstractController {

}

class MyCustomController extends MyAbstractController
{

}

?>
-----
<?php

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Controller\AbstractController;

class MyAbstractController extends ActionController {

}

class MyCustomController extends MyAbstractController
{

}

?>
