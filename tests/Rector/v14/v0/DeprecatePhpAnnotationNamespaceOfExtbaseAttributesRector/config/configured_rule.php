<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->phpVersion(PhpVersionFeature::ATTRIBUTES);
    $rectorConfig->ruleWithConfiguration(
        RenameClassRector::class,
        [
            'TYPO3\CMS\Extbase\Annotation\FileUpload' => 'TYPO3\CMS\Extbase\Attribute\FileUpload',
            'TYPO3\CMS\Extbase\Annotation\IgnoreValidation' => 'TYPO3\CMS\Extbase\Attribute\IgnoreValidation',
            'TYPO3\CMS\Extbase\Annotation\Validate' => 'TYPO3\CMS\Extbase\Attribute\Validate',
            'TYPO3\CMS\Extbase\Annotation\ORM\Cascade' => 'TYPO3\CMS\Extbase\Attribute\ORM\Cascade',
            'TYPO3\CMS\Extbase\Annotation\ORM\Lazy' => 'TYPO3\CMS\Extbase\Attribute\ORM\Lazy',
            'TYPO3\CMS\Extbase\Annotation\ORM\Transient' => 'TYPO3\CMS\Extbase\Attribute\ORM\Transient',
        ]
    );
};
