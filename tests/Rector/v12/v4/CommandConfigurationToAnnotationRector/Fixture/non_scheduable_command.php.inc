<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\CommandConfigurationToAnnotationRector\Fixture;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MyNonScheduableCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}

?>
