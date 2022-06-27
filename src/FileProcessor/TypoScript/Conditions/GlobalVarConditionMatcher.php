<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions;

use Rector\Core\Exception\ShouldNotHappenException;
use Ssch\TYPO3Rector\Helper\ArrayUtility;

final class GlobalVarConditionMatcher extends AbstractGlobalConditionMatcher
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
                '#(?<type>TSFE|GP|GPmerged|_POST|_GET|LIT|ENV|IENV|BE_USER)' . self::ZERO_ONE_OR_MORE_WHITESPACES . '[:|]' . self::ZERO_ONE_OR_MORE_WHITESPACES . '(?<property>.*)\s*(?<operator>' . self::ALLOWED_OPERATORS_REGEX . ')' . self::ZERO_ONE_OR_MORE_WHITESPACES . '(?<value>.*)$#Ui',
                $subCondition,
                $matches
            );

            if (! is_array($matches)) {
                continue;
            }

            $type = isset($matches['type']) ? trim((string) $matches['type']) : '';
            $property = isset($matches['property']) ? trim((string) $matches['property']) : '';
            $operator = isset($matches['operator']) ? trim((string) $matches['operator']) : '';
            $value = isset($matches[self::VALUE]) ? trim((string) $matches[self::VALUE]) : '';

            $key = sprintf('%s.%s.%s', $type, $property, $operator);

            if (! isset($conditions[$key])) {
                $conditions[$key] = [];
            }

            $conditions[$key][] = match ($type) {
                'TSFE' => $this->refactorTsfe($property, $operator, $value),
                'GP' => $this->refactorGetPost($property, $operator, $value),
                'LIT' => sprintf('"%s" %s "%s"', $value, self::OPERATOR_MAPPING[$operator], $property),
                'ENV' => $this->createEnvCondition($property, $operator, $value),
                'IENV' => $this->createIndependentCondition($property, $operator, $value),
                'BE_USER' => $this->createBackendUserCondition($property, $operator, $value),
                '_GET' => $this->refactorGet($property, $operator, $value),
                'GPmerged' => $this->refactorGetPost($property, $operator, $value),
                '_POST' => $this->refactorPost($property, $operator, $value),
                default => $condition,
            };
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

                $newConditions[] = sprintf('%s in [%s]', trim((string) $condition), trim(implode(',', $values)));
            } else {
                $newConditions[] = implode(' || ', $conditions[$key]);
            }
        }

        return implode(' || ', $newConditions);
    }

    public function shouldApply(string $condition): bool
    {
        return \str_starts_with($condition, self::TYPE);
    }

    private function refactorGetPost(string $property, string $operator, string $value): string
    {
        if ('L' === $property) {
            return sprintf('siteLanguage("languageId") %s "%s"', self::OPERATOR_MAPPING[$operator], $value);
        }

        $normalizedValue = $this->normalizeValue($value);

        $parameters = $this->explodeParameters($property);

        if (1 === count($parameters)) {
            return sprintf(
                'request.getQueryParams()[\'%1$s\'] %2$s %3$s',
                $parameters[0],
                self::OPERATOR_MAPPING[$operator],
                $normalizedValue
            );
        }

        if ('_POST' === $property) {
            return sprintf(
                'traverse(request.getParsedBody(), \'%1$s\') %2$s %3$s) %2$s %3$s',
                implode('/', $parameters),
                self::OPERATOR_MAPPING[$operator],
                $normalizedValue
            );
        }

        return sprintf(
            'traverse(request.getQueryParams(), \'%1$s\') %2$s %3$s || traverse(request.getParsedBody(), \'%1$s\') %2$s %3$s',
            implode('/', $parameters),
            self::OPERATOR_MAPPING[$operator],
            $normalizedValue
        );
    }

    private function createBackendUserCondition(string $property, string $operator, string $value): string
    {
        $delimiter = \str_contains($property, ':') ? ':' : '|';

        [, $property] = ArrayUtility::trimExplode($delimiter, $property, true, 2);

        if (! array_key_exists($property, self::USER_PROPERTY_MAPPING)) {
            $message = sprintf('The property "%s" can not be mapped for condition BE_USER', $property);
            throw new ShouldNotHappenException($message);
        }

        return sprintf(
            'backend.user.%s %s %s',
            self::USER_PROPERTY_MAPPING[$property],
            self::OPERATOR_MAPPING[$operator],
            $value
        );
    }

    private function refactorGet(string $property, string $operator, string $value): string
    {
        $normalizedValue = $this->normalizeValue($value);

        $parameters = $this->explodeParameters($property);

        return sprintf(
            'traverse(request.getQueryParams(), \'%1$s\') %2$s %3$s)',
            implode('/', $parameters),
            self::OPERATOR_MAPPING[$operator],
            $normalizedValue
        );
    }

    private function refactorPost(string $property, string $operator, string $value): string
    {
        $normalizedValue = $this->normalizeValue($value);

        $parameters = $this->explodeParameters($property);

        return sprintf(
            'traverse(request.getParsedBody(), \'%1$s\') %2$s %3$s)',
            implode('/', $parameters),
            self::OPERATOR_MAPPING[$operator],
            $normalizedValue
        );
    }

    private function normalizeValue(int|string $value): int|string
    {
        if (! is_numeric($value)) {
            return sprintf("'%s'", $value);
        }

        return $value;
    }

    /**
     * @return string[]
     */
    private function explodeParameters(string $property): array
    {
        return ArrayUtility::trimExplode('|', $property);
    }
}
