<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Set;

use Nette\Utils\Strings;
use Rector\Core\Exception\ShouldNotHappenException;
use ReflectionClass;
use Stringy\Stringy;
use Symplify\SetConfigResolver\Contract\SetProviderInterface;
use Symplify\SetConfigResolver\Exception\SetNotFoundException;
use Symplify\SetConfigResolver\Provider\AbstractSetProvider;
use Symplify\SetConfigResolver\ValueObject\Set;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @todo this class is actually not needed anymore; only direct constants with file paths are used
 */
final class Typo3RectorSetProvider extends AbstractSetProvider
{
    /**
     * @var string
     * @see https://regex101.com/r/8gO8w6/1
     */
    private const DASH_NUMBER_REGEX = '#\-(\d+)#';

    /**
     * @var SetProviderInterface
     */
    private $rectorSetProvider;

    /**
     * @var Set[]
     */
    private $sets = [];

    public function __construct(SetProviderInterface $rectorSetProvider)
    {
        $setListReflectionClass = new ReflectionClass(Typo3SetList::class);
        $this->hydrateSetsFromConstants($setListReflectionClass);
        $this->rectorSetProvider = $rectorSetProvider;
    }

    public function provide(): array
    {
        return array_merge($this->sets, $this->rectorSetProvider->provide());
    }

    public function provideByName(string $desiredSetName): ?Set
    {
        try {
            $foundSet = parent::provideByName($desiredSetName);
            if ($foundSet instanceof Set) {
                return $foundSet;
            }

            // second approach by set path
            foreach ($this->sets as $set) {
                if (! file_exists($desiredSetName)) {
                    continue;
                }

                $desiredSetFileInfo = new SmartFileInfo($desiredSetName);
                if ($set->getSetFileInfo()->getRealPath() !== $desiredSetFileInfo->getRealPath()) {
                    continue;
                }

                return $set;
            }

            $message = sprintf('Set "%s" was not found', $desiredSetName);
            throw new SetNotFoundException($message, $desiredSetName, $this->provideSetNames());
        } catch (SetNotFoundException $setNotFoundException) {
            return $this->rectorSetProvider->provideByName($desiredSetName);
        }
    }

    private function hydrateSetsFromConstants(ReflectionClass $setListReflectionClass): void
    {
        foreach ($setListReflectionClass->getConstants() as $name => $setPath) {
            if (! file_exists($setPath)) {
                $message = sprintf('Set path "%s" was not found', $name);
                throw new ShouldNotHappenException($message);
            }

            $stringy = new Stringy($name);
            $setName = (string) $stringy->dasherize();

            // remove `-` before numbers
            $setName = Strings::replace($setName, self::DASH_NUMBER_REGEX, '$1');
            $this->sets[] = new Set($setName, new SmartFileInfo($setPath));
        }
    }
}
