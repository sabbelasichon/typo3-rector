<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v3\MigrateGeneralUtilityGetIndpEnvRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

$variable = 'HTTP_HOST';
$array = GeneralUtility::getIndpEnv('_ARRAY');
$dynamic = GeneralUtility::getIndpEnv($variable);
$unknown = GeneralUtility::getIndpEnv('UNKNOWN_ENV');
