<?php

namespace Ssch\TYPO3Rector\TypoScript\Conditions;

interface TyposcriptConditionMatcher
{
    /**
     * @var array
     */
    public const OPERATOR_MAPPING = [
        '=' => '==',
        '>=' => '>=',
        '<=' => '<=',
        '>' => '>',
        '<' => '<',
        '!=' => '!=',
    ];

    /**
     * @var string
     */
    public const ALLOWED_OPERATORS_REGEX = '\<\=|\>\=|\!\=|\=|\>|\<';

    /**
     * @var string
     */
    public const ZERO_ONE_OR_MORE_WHITESPACES = '\s*';

    /**
     * If we return null it means conditions can be removed
     */
    public function change(string $condition): ?string;

    public function shouldApply(string $condition): bool;
}
