<?php

$composerAutoLoader = require dirname(__DIR__) . '/autoload.php';
$classAliasMap = require dirname(__DIR__) . '/composer/autoload_classaliasmap.php';
$classAliasLoader = new TYPO3\ClassAliasLoader\ClassAliasLoader($composerAutoLoader);
$classAliasLoader->setAliasMap($classAliasMap);
$classAliasLoader->setCaseSensitiveClassLoading('{$sensitive-loading}');
$classAliasLoader->register('{$prepend}');
TYPO3\ClassAliasLoader\ClassAliasMap::setClassAliasLoader($classAliasLoader);
