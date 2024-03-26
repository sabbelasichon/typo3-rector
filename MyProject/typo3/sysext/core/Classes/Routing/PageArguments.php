<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Core\Routing;

use TYPO3\CMS\Core\Utility\ArrayUtility;

/**
 * Contains all resolved parameters when a page is resolved from a page path segment plus all fragments.
 */
class PageArguments implements RouteResultInterface
{
    protected int $pageId;
    protected string $pageType;
    protected bool $dirty = false;

    /**
     * All (merged) arguments of this URI (routeArguments + dynamicArguments)
     *
     * @var array<string, string|array>
     */
    protected array $arguments;

    /**
     * Route arguments mapped by static mappers
     * "static" means the provided values in a URI maps to a finite number of values
     * (routeArguments - "arguments mapped by non static mapper")
     *
     * @var array<string, string|array>
     */
    protected array $staticArguments;

    /**
     * Route arguments, that have an infinite number of possible values
     * AND query string arguments. These arguments require a cHash.
     *
     * @var array<string, string|array>
     */
    protected array $dynamicArguments;

    /**
     * Arguments defined in and mapped by a route enhancer
     *
     * @var array<string, string|array>
     */
    protected array $routeArguments;

    /**
     * Query arguments in the generated URI
     *
     * @var array<string, string|array>
     */
    protected array $queryArguments = [];

    public function __construct(int $pageId, string $pageType, array $routeArguments, array $staticArguments = [], array $remainingArguments = [])
    {
        $this->pageId = $pageId;
        $this->pageType = $pageType;
        $this->routeArguments = $this->sort($routeArguments);
        $this->staticArguments = $this->sort($staticArguments);
        $this->arguments = $this->routeArguments;
        $this->updateDynamicArguments();
        if (!empty($remainingArguments)) {
            $this->updateQueryArguments($remainingArguments);
        }
    }

    public function areDirty(): bool
    {
        return $this->dirty;
    }

    /**
     * @return array<string, string|array>
     */
    public function getRouteArguments(): array
    {
        return $this->routeArguments;
    }

    public function getPageId(): int
    {
        return $this->pageId;
    }

    public function getPageType(): string
    {
        return $this->pageType;
    }

    /**
     * @return string|array<string, string|array>|null
     */
    public function get(string $name): mixed
    {
        return $this->arguments[$name] ?? null;
    }

    /**
     * @return array<string, string|array>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @return array<string, string|array>
     */
    public function getStaticArguments(): array
    {
        return $this->staticArguments;
    }

    /**
     * @return array<string, string|array>
     */
    public function getDynamicArguments(): array
    {
        return $this->dynamicArguments;
    }

    /**
     * @return array<string, string|array>
     */
    public function getQueryArguments(): array
    {
        return $this->queryArguments;
    }

    /**
     * @param array<string, string|array> $queryArguments
     */
    protected function updateQueryArguments(array $queryArguments)
    {
        $queryArguments = $this->sort($queryArguments);
        if ($this->queryArguments === $queryArguments) {
            return;
        }
        // in case query arguments would override route arguments,
        // the state is considered as dirty (since it's not distinct)
        // thus, route arguments take precedence over query arguments
        $additionalQueryArguments = $this->diff($queryArguments, $this->routeArguments);
        $dirty = $additionalQueryArguments !== $queryArguments;
        $this->dirty = $this->dirty || $dirty;
        $this->queryArguments = $queryArguments;
        $this->arguments = array_replace_recursive($this->arguments, $additionalQueryArguments);
        $this->updateDynamicArguments();
    }

    /**
     * Updates dynamic arguments based on definitions for static arguments.
     */
    protected function updateDynamicArguments(): void
    {
        $this->dynamicArguments = $this->diff(
            $this->arguments,
            $this->staticArguments
        );
    }

    /**
     * Cleans empty array recursively.
     *
     * @param array<string, string|array> $array
     */
    protected function clean(array $array): array
    {
        foreach ($array as $key => &$item) {
            if (!is_array($item)) {
                continue;
            }
            if (!empty($item)) {
                $item = $this->clean($item);
            }
            if (empty($item)) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    /**
     * Sorts array keys recursively.
     *
     * @param array<string, string|array> $array
     */
    protected function sort(array $array): array
    {
        $array = $this->clean($array);
        ArrayUtility::naturalKeySortRecursive($array);
        return $array;
    }

    /**
     * Removes keys that are defined in $second from $first recursively.
     *
     * @param array<string, string|array> $first
     * @param array<string, string|array> $second
     */
    protected function diff(array $first, array $second): array
    {
        return ArrayUtility::arrayDiffKeyRecursive($first, $second);
    }

    public function offsetExists(mixed $offset): bool
    {
        return $offset === 'pageId' || $offset === 'pageType' || isset($this->arguments[$offset]);
    }

    /**
     * @return string|array<string, string|array>|null
     */
    public function offsetGet(mixed $offset): mixed
    {
        if ($offset === 'pageId') {
            return $this->getPageId();
        }
        if ($offset === 'pageType') {
            return $this->getPageType();
        }
        return $this->arguments[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \InvalidArgumentException('PageArguments cannot be modified.', 1538152266);
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \InvalidArgumentException('PageArguments cannot be modified.', 1538152269);
    }
}
