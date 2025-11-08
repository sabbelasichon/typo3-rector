<?php

declare(strict_types=1);

use PHPStan\Type\BooleanType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\UnionType;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\TYPO314\v0\RemoveParameterInAuthenticationServiceRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->phpVersion(PhpVersion::PHP_81);
    $rectorConfig->rule(RemoveParameterInAuthenticationServiceRector::class);
    $rectorConfig->ruleWithConfiguration(
        AddReturnTypeDeclarationRector::class,
        [
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Authentication\AuthenticationService',
                'processLoginData',
                new UnionType([new BooleanType(), new IntegerType()])
            ),
        ]
    );
};
