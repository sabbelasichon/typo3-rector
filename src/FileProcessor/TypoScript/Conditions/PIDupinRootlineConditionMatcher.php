<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions;

final class PIDupinRootlineConditionMatcher extends AbstractRootlineConditionMatcher
{
    /**
     * @var string
     */
    private const TYPE = 'PIDupinRootline';

    protected function getType(): string
    {
        return self::TYPE;
    }

    protected function getExpression(): string
    {
        return 'tree.rootLineParentIds';
    }
}
