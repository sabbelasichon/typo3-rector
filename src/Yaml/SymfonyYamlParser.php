<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Yaml;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

final class SymfonyYamlParser
{
    /**
     * @return mixed
     */
    public function parse(string $pathToFile, string $content)
    {
        try {
            return Yaml::parse($content, Yaml::PARSE_CUSTOM_TAGS);
        } catch (ParseException $parseException) {
            $parseException->setParsedFile($pathToFile);

            throw $parseException;
        }
    }

    /**
     * @param mixed $input
     */
    public function dump($input, int $indent = 4): string
    {
        return Yaml::dump($input, 99, $indent);
    }
}
