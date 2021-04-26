<?php

$template = $GLOBALS['TSFE']->cObj->fileResource('EXT:vendor/Resources/Private/Templates/Template.html');

?>
-----
<?php

$template = '';
$typoscriptFrontendController = $GLOBALS['TSFE'];
$path = $typoscriptFrontendController->tmpl->getFileName('EXT:vendor/Resources/Private/Templates/Template.html');
if ($path !== null && file_exists($path)) {
    $template = file_get_contents($path);
}
