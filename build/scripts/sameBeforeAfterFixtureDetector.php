#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

if (PHP_SAPI !== 'cli') {
    die('Script must be called from command line.' . chr(10));
}

use Nette\Utils\Strings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Webmozart\Assert\Assert;

/**
 * @source https://github.com/rectorphp/rector-src/blob/e20f1c9d767fcb998df80c03c577b8762b7563aa/scripts/check-before-after-same-fixtures.php
 */
final class sameBeforeAfterFixtureDetector
{
    private SymfonyStyle $symfonyStyle;

    public function __construct()
    {
        $this->symfonyStyle = new SymfonyStyle(new ArgvInput(), new ConsoleOutput());
    }

    /**
     * @param string[] $testDirectories
     * @return Command::SUCCESS|Command::FAILURE
     */
    public function run(array $testDirectories): int
    {
        $fixtureFiles = self::find($testDirectories);

        $invalidFixturePaths = [];
        foreach ($fixtureFiles as $fixtureFile) {
            if (! $this->hasFileSameBeforeAndAfterPart($fixtureFile)) {
                continue;
            }

            $invalidFixturePaths[] = substr($fixtureFile->getRealPath(), strlen(getcwd()) + 1);
        }

        if ($invalidFixturePaths !== []) {
            $this->symfonyStyle->error(
                'The following fixtures have the same before and after content. Remove the part after "-----" to fix them'
            );
            $this->symfonyStyle->listing($invalidFixturePaths);

            return Command::FAILURE;
        }

        $this->symfonyStyle->success(sprintf('All %d fixtures are valid', count($fixtureFiles)));
        return Command::SUCCESS;
    }

    /**
     * @param string[] $directories
     * @return SplFileInfo[]
     */
    private static function find(array $directories): array
    {
        Assert::allDirectory($directories);

        $finder = (new Finder())
            ->files()
            ->in($directories)
            ->name('*.php.inc')
            ->sortByName();

        return iterator_to_array($finder->getIterator());
    }

    private function hasFileSameBeforeAndAfterPart(SplFileInfo $fixtureFile): bool
    {
        $parts = Strings::split($fixtureFile->getContents(), '#^\s*-----\s*$#m');
        if (count($parts) !== 2) {
            return false;
        }

        return trim((string) $parts[0]) === trim((string) $parts[1]);
    }
}

$sameBeforeAfterFixtureDetector = new sameBeforeAfterFixtureDetector();
exit($sameBeforeAfterFixtureDetector->run([__DIR__ . '/../../tests/Rector']));
