<?php
declare(strict_types=1);


namespace Ssch\TYPO3Rector\ValueObject;


final class CompleteImportForPartialAnnotation
{
    /**
     * @readonly
     */
    private string $use;

    /**
     * @readonly
     */
    private string $alias;

    public function __construct(
        string $use,
        string $alias
    ) {
        $this->use = $use;
        $this->alias = $alias;
    }

    public function getUse(): string
    {
        return $this->use;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }
}
