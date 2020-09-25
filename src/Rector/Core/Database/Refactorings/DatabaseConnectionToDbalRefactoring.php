<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\Database\Refactorings;

use PhpParser\Node\Expr\MethodCall;

interface DatabaseConnectionToDbalRefactoring
{
    public function refactor(MethodCall $oldNode): array;

    public function canHandle(string $methodName): bool;
}
