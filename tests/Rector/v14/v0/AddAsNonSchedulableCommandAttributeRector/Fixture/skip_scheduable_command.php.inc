<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\AddAsNonSchedulableCommandAttributeRector\Fixture;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MyScheduableCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
    }
}
