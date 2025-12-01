<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\IntroduceStrictTypingForCacheBeAndFeRector\Fixture;

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

class MyCustomFrontend implements FrontendInterface
{
    public function getIdentifier()
    {
    }

    public function getBackend()
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

    public function flushByTag($tag)
    {
    }

    public function flushByTags(array $tags)
    {
    }

    public function collectGarbage()
    {
    }

    public function isValidEntryIdentifier($identifier)
    {
    }

    public function isValidTag($tag)
    {
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\IntroduceStrictTypingForCacheBeAndFeRector\Fixture;

use TYPO3\CMS\Core\Cache\Backend\BackendInterface;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

class MyCustomFrontend implements FrontendInterface
{
    public function getIdentifier(): string
    {
    }

    public function getBackend(): BackendInterface
    {
    }

    public function set(string $entryIdentifier, mixed $data, array $tags = [], ?int $lifetime = null): void
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

    public function flushByTag(string $tag): void
    {
    }

    public function flushByTags(array $tags): void
    {
    }

    public function collectGarbage(): void
    {
    }

    public function isValidEntryIdentifier(string $identifier): bool
    {
    }

    public function isValidTag(string $tag): bool
    {
    }
}

?>
