<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript\Conditions;

use Nette\Utils\Strings;
use Ssch\TYPO3Rector\ArrayUtility;

final class GlobalVarConditionMatcher implements TyposcriptConditionMatcher
{
    /**
     * @var string
     */
    private const TYPE = 'globalVar';

    /**
     * @var string
     */
    private const VALUE = 'value';

    public function change(string $condition): ?string
    {
        preg_match('#' . self::TYPE . '\s*=\s*(?<subCondition>.*)#', $condition, $subConditions);

        if (! is_string($subConditions['subCondition'])) {
            return $condition;
        }

        $subConditions = ArrayUtility::trimExplode(',', $subConditions['subCondition']);

        $conditions = [];
        foreach ($subConditions as $subCondition) {
            preg_match(
                '#(?<type>TSFE|GP|GPmerged|_POST|_GET|LIT)\s*:\s*(?<property>.*)\s*(?<operator>' . self::ALLOWED_OPERATORS_REGEX . ')\s*(?<value>.*)$#Ui',
                $subCondition,
                $matches
            );

            if (! is_array($matches)) {
                continue;
            }

            $type = isset($matches['type']) ? trim($matches['type']) : '';
            $property = isset($matches['property']) ? trim($matches['property']) : '';
            $operator = isset($matches['operator']) ? trim($matches['operator']) : '';
            $value = isset($matches[self::VALUE]) ? trim($matches[self::VALUE]) : '';

            $key = sprintf('%s.%s.%s', $type, $property, $operator);

            if (! isset($conditions[$key])) {
                $conditions[$key] = [];
            }

            if ('TSFE' === $type) {
                $conditions[$key][] = $this->refactorTsfe($property, $operator, $value);
                continue;
            }

            if ('GP' === $type) {
                $conditions[$key][] = $this->refactorGetPost($property, $operator, $value);
                continue;
            }

            if ('LIT' === $type) {
                $conditions[$key][] = sprintf('"%s" %s "%s"', $value, self::OPERATOR_MAPPING[$operator], $property);
                continue;
            }
        }

        $keys = array_keys($conditions);

        $newConditions = [];
        foreach ($keys as $key) {
            [, , $operator] = explode('.', $key);

            if ('=' === $operator && is_countable($conditions[$key]) && count($conditions[$key]) > 1) {
                $values = [];
                $condition = '';
                foreach ($conditions[$key] as $value) {
                    preg_match('#(?<condition>.*)\s*==\s*(?<value>.*)#', $value, $valueMatches);

                    if (! is_array($valueMatches)) {
                        continue;
                    }

                    $values[] = $valueMatches[self::VALUE];
                    $condition = $valueMatches['condition'];
                }

                $newConditions[] = sprintf('%s in [%s]', trim($condition), trim(implode(',', $values)));
            } else {
                $newConditions[] = implode(' || ', $conditions[$key]);
            }
        }

        return implode(' || ', $newConditions);
    }

    public function shouldApply(string $condition): bool
    {
        return Strings::startsWith($condition, self::TYPE);
    }

    private function refactorGetPost(string $property, string $operator, string $value): string
    {
        if ('L' === $property) {
            return sprintf('siteLanguage("languageId") %s "%s"', self::OPERATOR_MAPPING[$operator], $value);
        }

        $parameters = ArrayUtility::trimExplode('|', $property);

        if (1 === count($parameters)) {
            return sprintf('request.getQueryParams()[\'%1$s\'] %2$s %3$s', $parameters[0], $operator, $value);
        }

        return sprintf(
            'traverse(request.getQueryParams(), \'%1$s\') %2$s %3$s || traverse(request.getParsedBody(), \'%1$s\') %2$s %3$s',
            implode('/', $parameters),
            $operator,
            $value
        );
    }

    private function refactorTsfe(string $property, string $operator, string $value): string
    {
        if (Strings::startsWith($property, 'page')) {
            $parameters = ArrayUtility::trimExplode('|', $property, true);
            return sprintf('page["%s"] %s %s', $parameters[1], self::OPERATOR_MAPPING[$operator], $value);
        }

        return sprintf('getTSFE().%s %s %s', $property, self::OPERATOR_MAPPING[$operator], $value);
    }
}
