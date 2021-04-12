<?php

declare(strict_types=1);

use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use Ssch\TYPO3Rector\Compiler\PhpScoper\StaticEasyPrefixer;
use Ssch\TYPO3Rector\Compiler\Unprefixer;
use Ssch\TYPO3Rector\Compiler\ValueObject\ScoperOption;

require_once __DIR__ . '/vendor/autoload.php';

// [BEWARE] this path is relative to the root and location of this file
$filePathsToSkip = [
    // @see https://github.com/rectorphp/rector/issues/2852#issuecomment-586315588
    'vendor/symfony/deprecation-contracts/function.php',
    'stubs/SolrPhpClient/Apache_Solr_Document.php',
    'stubs/SolrPhpClient/Apache_Solr_Response.php',
    'src/Helper/Strings.php',
];

// remove phpstan, because it is already prefixed in its own scope

$dateTime = DateTime::from('now');
$timestamp = $dateTime->format('Ymd');

// see https://github.com/humbug/php-scoper
return [
    ScoperOption::PREFIX => 'Typo3RectorPrefix' . $timestamp,
    ScoperOption::FILES_WHITELIST => $filePathsToSkip,
    ScoperOption::WHITELIST => StaticEasyPrefixer::getExcludedNamespacesAndClasses(),
    ScoperOption::PATCHERS => [
        // [BEWARE] $filePath is absolute!

        // fixes https://github.com/rectorphp/rector-prefixed/runs/2103759172
        // and https://github.com/rectorphp/rector-prefixed/blob/0cc433e746b645df5f905fa038573c3a1a9634f0/vendor/jean85/pretty-package-versions/src/PrettyVersions.php#L6
        function (string $filePath, string $prefix, string $content): string {
            if (! Strings::endsWith($filePath, 'vendor/jean85/pretty-package-versions/src/PrettyVersions.php')) {
                return $content;
            }

            // see https://regex101.com/r/v8zRMm/1
            return Strings::replace($content, '#' . $prefix . '\\\\Composer\\\\InstalledVersions#', 'Composer\InstalledVersions');
        },

        // fixes https://github.com/rectorphp/rector/issues/6007
        function (string $filePath, string $prefix, string $content): string {
            if (! Strings::contains($filePath, 'vendor/')) {
                return $content;
            }

            // @see https://regex101.com/r/lBV8IO/2
            $fqcnReservedPattern = sprintf('#(\\\\)?%s\\\\(parent|self|static)#m', $prefix);
            $matches             = Strings::matchAll($content, $fqcnReservedPattern);

            if (! $matches) {
                return $content;
            }

            foreach ($matches as $match) {
                $content = str_replace($match[0], $match[2], $content);
            }

            return $content;
        },

        // fixes https://github.com/rectorphp/rector/issues/6010
        function (string $filePath, string $prefix, string $content): string {
            // @see https://regex101.com/r/bA1nQa/1
            if (! Strings::match($filePath, '#vendor/symfony/polyfill-php\d{2}/Resources/stubs#')) {
                return $content;
            }

            // @see https://regex101.com/r/x5Ukrx/1
            $namespace = sprintf('#namespace %s;#m', $prefix);
            return Strings::replace($content, $namespace);
        },

        // unprefix string classes, as they're string on purpose - they have to be checked in original form, not prefixed
        function (string $filePath, string $prefix, string $content): string {
            // skip vendor
            if (Strings::contains($filePath, 'vendor/')) {
                return $content;
            }

            // skip bin/rector.php for composer autoload class
            if (Strings::endsWith($filePath, 'bin/rector.php')) {
                return $content;
            }

            // skip scoper-autoload
            if (Strings::endsWith($filePath, 'vendor/scoper-autoload.php')) {
                return $content;
            }

            return Unprefixer::unprefixQuoted($content, $prefix);
        },

        // scoper missed PSR-4 autodiscovery in Symfony
        function (string $filePath, string $prefix, string $content): string {


            // scoper missed PSR-4 autodiscovery in Symfony
            if (! Strings::endsWith($filePath, 'config.php') && ! Strings::endsWith($filePath, 'services.php')) {
                return $content;
            }

            // skip "Rector\\" namespace
            if (Strings::contains($content, '$services->load(\'Rector')) {
                return $content;
            }

            // skip "Ssch\\" namespace
            if (Strings::contains($content, '$services->load(\'Ssch')) {
                return $content;
            }

            return Strings::replace($content, '#services\->load\(\'#', 'services->load(\'' . $prefix . '\\');
        },
    ],
];
