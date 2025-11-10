<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\GetClickMenuOnIconTagParametersRector\Fixture;

use TYPO3\CMS\Backend\Utility\BackendUtility;

BackendUtility::wrapClickMenuOnIcon('pages', 1, 'foo', '', []);

$array = [];
BackendUtility::wrapClickMenuOnIcon('pages', 1, 'foo', '', $array);
