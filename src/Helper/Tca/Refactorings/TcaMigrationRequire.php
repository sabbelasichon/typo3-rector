<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper\Tca\Refactorings;

use Nette\Utils\FileSystem;
use Symplify\SmartFileSystem\SmartFileInfo;

trait TcaMigrationRequire
{
    public function migrate(array $tca): array
    {
        $namespace = $this->getNamespace();
        $pathToFile = $this->require($namespace);

        $class = sprintf('%s\TcaMigration', $namespace);

        $tcaMigration = new $class();

        if (! isset($tca['table'])) {
            $tca = [
                'table' => $tca,
            ];
        }

        if (is_array($tca)) {
            $tca = $tcaMigration->migrate($tca);
        }

        $smartFileInfo = new SmartFileInfo($pathToFile);

        FileSystem::delete($smartFileInfo->getRealPath());

        return $tca;
    }

    public function require(string $namespace): string
    {
        $file = __DIR__ . sprintf(
            '/../../../../Migrations/TYPO3/%s/typo3/sysext/core/Migrations/TcaMigration.php',
                $this->getVersion()
        );

        $content = (string) file_get_contents($file);
        if (false !== strpos($content, 'namespace TYPO3\CMS\Core\Migrations;')) {
            $content = str_replace(
                'namespace TYPO3\CMS\Core\Migrations;',
                sprintf('namespace %s;', $namespace),
                $content
            );
        }

        $pathToFile = (string) tempnam(sys_get_temp_dir(), 'tca');
        file_put_contents($pathToFile, $content);

        require_once($pathToFile);

        return $pathToFile;
    }

    private function getNamespace(): string
    {
        return sprintf('TYPO3\CMS\Core\Migrations_%s', str_replace('.', '_', static::VERSION . random_int(1, 100000)));
    }
}
