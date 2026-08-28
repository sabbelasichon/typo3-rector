<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO312\AnnotationToAttribute\AttributeDecorator;
use Ssch\TYPO3Rector\TYPO312\AnnotationToAttribute\CascadeAttributeDecorator;
use Ssch\TYPO3Rector\TYPO312\AnnotationToAttribute\IgnoreValidationAttributeDecorator;
use Ssch\TYPO3Rector\TYPO312\AnnotationToAttribute\ValidateAttributeDecorator;
use Ssch\TYPO3Rector\TYPO312\v0\ExtbaseAnnotationToAttributeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->singleton(CascadeAttributeDecorator::class);
    $rectorConfig->singleton(IgnoreValidationAttributeDecorator::class);
    $rectorConfig->singleton(ValidateAttributeDecorator::class);
    $rectorConfig->singleton(
        AttributeDecorator::class,
        static fn (RectorConfig $app): AttributeDecorator => new AttributeDecorator([
            $app->make(CascadeAttributeDecorator::class),
            $app->make(IgnoreValidationAttributeDecorator::class),
            $app->make(ValidateAttributeDecorator::class),
        ])
    );
    $rectorConfig->rule(ExtbaseAnnotationToAttributeRector::class);
};
