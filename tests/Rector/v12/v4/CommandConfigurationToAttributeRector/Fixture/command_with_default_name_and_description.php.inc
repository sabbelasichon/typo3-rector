<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\CommandConfigurationToAttributeRector\Fixture;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MySpecialCommand extends Command
{
    protected static $defaultName = 'my:command';

    protected static $defaultDescription = 'My command description';
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\CommandConfigurationToAttributeRector\Fixture;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'my:command', description: 'My command description', hidden: true)]
class MySpecialCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}

?>
