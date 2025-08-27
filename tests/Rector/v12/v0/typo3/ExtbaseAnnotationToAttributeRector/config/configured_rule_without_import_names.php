<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames(false, false);
    // this will not import root namespace classes, like \DateTime or \Exception
    $rectorConfig->importShortClasses(false);
    $rectorConfig->phpVersion(PhpVersionFeature::ATTRIBUTES);
    $rectorConfig->import(__DIR__ . '/../../../../../../../config/v12/extbase_annotations_to_attributes.php');
};
