<?php

declare(strict_types=1);


use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\Set\ValueObject\LevelSetList;
use Ssch\TYPO3Rector\Set\Typo3LevelSetList;


class Helper {

    protected static  $printInfo = false;

    public static $dirProjectSources = __DIR__;

    public static $dirTypo3Sources = __DIR__;


    public static function  init($dir = __DIR__)
    {
        //static::$dirProjectSources = static::getRealDir('project_src', $dir);
        //static::$dirTypo3Sources   = static::getRealDir('typo3_src', $dir);
    }

    public static function printInfo()
    {

        if (static::$printInfo) {
            echo PHP_EOL . 'Der Rector nutzt jetzt PHP-Version: ' . phpversion() . PHP_EOL ;
            echo PHP_EOL . '$dirProjectSources = ' . static::$dirProjectSources;
            echo PHP_EOL . '$dirTypo3Sources = '   . static::$dirTypo3Sources;
            echo PHP_EOL . PHP_EOL;
            static::$printInfo = true;
        }
    }

    protected static function getRealDir(string $subDir, $dir = __DIR__)
    {
        if (file_exists($dir . '/' . $subDir)) {
            $dir = realpath($dir . '/' . $subDir);
        }
        return $dir;
    }
}

$dir = __DIR__;
\Helper::init($dir);
\Helper::printInfo();

return RectorConfig::configure()
    ->withPaths([
        \Helper::$dirTypo3Sources . '/typo3',
        \Helper::$dirProjectSources . '/typo3conf',
    ])

    //Verzeichnisse die vomn rector übersprungen werden sollen Stand 12.02.2024
    ->withSkip([
        //systemverzeichnisse
        \Helper::$dirTypo3Sources . '/typo3/sysext',
        \Helper::$dirTypo3Sources . '/vendor',

    ])
    ->withSets([
        Typo3LevelSetList::UP_TO_TYPO3_12,
        LevelSetList::UP_TO_PHP_82,
    ])
    // uncomment to reach your current PHP version
    // ->withPhpSets()
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
    ]);


