<?php

final class TestCommandControllerWithFlushCaches
{
    /**
     * My Command
     * @flushCaches
     */
    public function someMethodCommand(): void
    {
    }
}

?>
-----
<?php

final class TestCommandControllerWithFlushCaches
{
    /**
     * My Command
     */
    public function someMethodCommand(): void
    {
    }
}

?>
