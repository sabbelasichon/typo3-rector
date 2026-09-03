<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\AddAsNonSchedulableCommandAttributeRector\Fixture;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MyNonScheduableCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\AddAsNonSchedulableCommandAttributeRector\Fixture;

use Symfony\Component\Console\Attribute\AsCommand;
use TYPO3\CMS\Core\Attribute\AsNonSchedulableCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'my:command', description: 'My command description', hidden: false)]
#[AsNonSchedulableCommand]
class MyNonScheduableCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
    }
}
?>
