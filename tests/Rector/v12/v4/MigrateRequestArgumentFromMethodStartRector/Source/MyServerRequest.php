<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateRequestArgumentFromMethodStartRector\Source;

use Psr\Http\Message\ServerRequestInterface;

final class MyServerRequest implements ServerRequestInterface
{
    public function getAttribute($name, $default)
    {
    }
}
