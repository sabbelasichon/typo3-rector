<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\Tests\ValueObject;

use PHPUnit\Framework\TestCase;
use Ssch\TYPO3Rector\Generator\ValueObject\Typo3RectorRecipe;
use Ssch\TYPO3Rector\ValueObject\Typo3Version;

class Typo3RectorRecipeTest extends TestCase
{
    public function testMajorVersionIsCorrect(): void
    {
        $typo3RectorRecipe = new Typo3RectorRecipe(Typo3Version::createFromString('8.7'));
        self::assertSame('v8', $typo3RectorRecipe->getMajorVersion());
    }

    public function testMinorVersionIsCorrect(): void
    {
        $typo3RectorRecipe = new Typo3RectorRecipe(Typo3Version::createFromString('8.7'));
        self::assertSame('v7', $typo3RectorRecipe->getMinorVersion());
    }
}
