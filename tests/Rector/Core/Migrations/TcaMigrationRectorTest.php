<?php

namespace Ssch\TYPO3Rector\Tests\Core\Migrations;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

class TcaMigrationRectorTest extends AbstractRectorWithConfigTestCase
{
    /**
     * @dataProvider provideDataForTest()
     *
     * @param string $file
     */
    public function test(string $file): void
    {
        $this->doTestFile($file);
    }

    public function provideDataForTest(): Iterator
    {
        yield [__DIR__ . '/Fixture/tca_migrations_render_type.php.inc'];
        yield [__DIR__ . '/Fixture/tca_migrations_config_none.php.inc'];
        yield [__DIR__ . '/Fixture/tca_migrations_request_update.php.inc'];
        yield [__DIR__ . '/Fixture/tca_migrations_icons_in_options_tags.php.inc'];
        yield [__DIR__ . '/Fixture/tca_migrations_wizards_enable_ty_type_config.php.inc'];
        yield [__DIR__ . '/Fixture/tca_migrations_input_date_time_max.php.inc'];
    }
}
