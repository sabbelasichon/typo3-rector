<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\typo3\MigrateMagicRepositoryMethodsRector\Source;

use TYPO3\CMS\Extbase\Persistence\Repository;

class ExampleRepository extends Repository
{
    public function findByMethodExists(string $random): void
    {
    }
}
