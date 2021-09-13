<?php

if (isset($TBE_MODULES['file'])) {
    $caretaker_modconf = $TBE_MODULES['txcaretakerNav'];
    unset($TBE_MODULES['txcaretakerNav']);
}
// move module after 'file'
$temp_TBE_MODULES = [];
foreach ($TBE_MODULES as $key => $value) {
    if ($key == 'file') {
        $temp_TBE_MODULES[$key] = $value;
        $temp_TBE_MODULES['txcaretakerNav'] = $caretaker_modconf;
    } else {
        $temp_TBE_MODULES[$key] = $value;
    }
}
$TBE_MODULES = $temp_TBE_MODULES;

?>
-----
<?php

if (isset($GLOBALS['TBE_MODULES']['file'])) {
    $caretaker_modconf = $GLOBALS['TBE_MODULES']['txcaretakerNav'];
    unset($GLOBALS['TBE_MODULES']['txcaretakerNav']);
}
// move module after 'file'
$temp_TBE_MODULES = [];
foreach ($GLOBALS['TBE_MODULES'] as $key => $value) {
    if ($key == 'file') {
        $temp_TBE_MODULES[$key] = $value;
        $temp_TBE_MODULES['txcaretakerNav'] = $caretaker_modconf;
    } else {
        $temp_TBE_MODULES[$key] = $value;
    }
}
$GLOBALS['TBE_MODULES'] = $temp_TBE_MODULES;

?>
