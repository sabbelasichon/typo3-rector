<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeFactory;

use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;

final class Typo3GlobalsFactory
{
    public function create(string $globalName): ArrayDimFetch
    {
        return new ArrayDimFetch(new Variable('GLOBALS'), new String_($globalName));
    }
}
