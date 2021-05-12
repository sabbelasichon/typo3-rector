<?php

declare(strict_types=1);

namespace Nimut\TestingFramework\MockObject;

if (interface_exists(AccessibleMockObjectInterface::class)) {
    return;
}

interface AccessibleMockObjectInterface
{

}
