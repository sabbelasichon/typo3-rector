<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Ssch\TYPO3Rector\AttributeDecorator\ExtbaseValidateAttributeDecorator;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $services = $rectorConfig->services();
    $rectorConfig->rule(StringClassNameToClassConstantRector::class);
    $services->set(ExtbaseValidateAttributeDecorator::class)
        ->autoconfigure()
        ->autowire();
    $rectorConfig->ruleWithConfiguration(AnnotationToAttributeRector::class, [
        new AnnotationToAttribute('TYPO3\\CMS\\Extbase\\Annotation\\ORM\\Lazy'),
        new AnnotationToAttribute('Extbase\\ORM\\Lazy', 'TYPO3\\CMS\\Extbase\\Annotation\\ORM\\Lazy'),
        new AnnotationToAttribute('TYPO3\\CMS\\Extbase\\Annotation\\ORM\\Transient'),
        new AnnotationToAttribute('Extbase\\ORM\\Transient', 'TYPO3\\CMS\\Extbase\\Annotation\\ORM\\Transient'),
        new AnnotationToAttribute('TYPO3\\CMS\\Extbase\\Annotation\\ORM\\Cascade'),
        new AnnotationToAttribute('Extbase\\ORM\\Cascade', 'TYPO3\\CMS\\Extbase\\Annotation\\ORM\\Cascade'),
        new AnnotationToAttribute('TYPO3\\CMS\\Extbase\\Annotation\\Validate'),
        new AnnotationToAttribute('Extbase\\Validate', 'TYPO3\\CMS\\Extbase\\Annotation\\Validate'),
        new AnnotationToAttribute('TYPO3\\CMS\\Extbase\\Annotation\\IgnoreValidation'),
        new AnnotationToAttribute('Extbase\\IgnoreValidation', 'TYPO3\\CMS\\Extbase\\Annotation\\IgnoreValidation'),
    ]);
};
