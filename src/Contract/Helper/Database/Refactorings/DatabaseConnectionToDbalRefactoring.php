<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Contract\Helper\Database\Refactorings;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;

interface DatabaseConnectionToDbalRefactoring
{
    public function refactor(MethodCall $oldMethodCall): ?Node;

    public function canHandle(string $methodName): bool;
}
