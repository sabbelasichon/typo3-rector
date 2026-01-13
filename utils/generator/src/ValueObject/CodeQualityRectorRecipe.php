<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\ValueObject;

use Ssch\TYPO3Rector\Generator\Contract\Typo3RectorTypeInterface;

final class CodeQualityRectorRecipe
{
    /**
     * @readonly
     */
    private string $name;

    /**
     * @readonly
     */
    private string $description;

    /**
     * @readonly
     */
    private Typo3RectorTypeInterface $type;

    public function __construct(string $name, string $description, Typo3RectorTypeInterface $type)
    {
        $this->name = $name;
        $this->description = $description;
        $this->type = $type;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getRectorName(): string
    {
        return $this->name . 'Rector';
    }

    public function getTestDirectory(): string
    {
        return $this->name . 'Rector';
    }

    public function getSet(): string
    {
        return __DIR__ . '/../../../../config/code-quality.php';
    }

    public function getRectorClass(): string
    {
        return $this->type->getRectorClass();
    }

    public function getRectorShortClassName(): string
    {
        return $this->type->getRectorShortClassName();
    }

    public function getRectorBodyTemplate(): string
    {
        return $this->type->getRectorBodyTemplate();
    }
}
