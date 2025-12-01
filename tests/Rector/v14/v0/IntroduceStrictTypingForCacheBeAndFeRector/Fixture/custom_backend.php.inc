<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\IntroduceStrictTypingForCacheBeAndFeRector\Fixture;

use TYPO3\CMS\Core\Cache\Backend\BackendInterface;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

class MyCustomBackend implements BackendInterface
{
    public function setCache(FrontendInterface $cache)
    {
    }

    public function set($entryIdentifier, $data, array $tags = [], $lifetime = null)
    {
    }

    public function get($entryIdentifier)
    {
    }

    public function has($entryIdentifier)
    {
    }

    public function remove($entryIdentifier)
    {
    }

    public function flush()
    {
    }

    public function collectGarbage()
    {
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\IntroduceStrictTypingForCacheBeAndFeRector\Fixture;

use TYPO3\CMS\Core\Cache\Backend\BackendInterface;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

class MyCustomBackend implements BackendInterface
{
    public function setCache(FrontendInterface $cache): void
    {
    }

    public function set(string $entryIdentifier, string $data, array $tags = [], ?int $lifetime = null): void
    {
    }

    public function get(string $entryIdentifier): mixed
    {
    }

    public function has(string $entryIdentifier): bool
    {
    }

    public function remove(string $entryIdentifier): bool
    {
    }

    public function flush(): void
    {
    }

    public function collectGarbage(): void
    {
    }
}

?>
