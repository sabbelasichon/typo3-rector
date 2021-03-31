<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Yaml\Form;

use Ssch\TYPO3Rector\Processor\ProcessorInterface;
use Ssch\TYPO3Rector\Yaml\Form\Transformer\FormYamlTransformer;
use Symfony\Component\Yaml\Yaml;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @see \Ssch\TYPO3Rector\Tests\Yaml\Form\FormYamlProcessorTest
 */
final class FormYamlProcessor implements ProcessorInterface
{
    /**
     * @var string[]
     */
    private const ALLOWED_FILE_EXTENSIONS = ['form.yaml'];

    /**
     * @var FormYamlTransformer[]
     */
    private $transformer = [];

    /**
     * @param FormYamlTransformer[] $transformer
     */
    public function __construct(array $transformer)
    {
        $this->transformer = $transformer;
    }

    public function process(SmartFileInfo $smartFileInfo): ?string
    {
        $yaml = Yaml::parseFile($smartFileInfo->getRealPath());

        if ([] === $this->transformer) {
            return $smartFileInfo->getContents();
        }

        if (! is_array($yaml)) {
            return $smartFileInfo->getContents();
        }

        foreach ($this->transformer as $transformer) {
            $yaml = $transformer->transform($yaml);
        }

        return Yaml::dump($yaml, 99, 2);
    }

    public function canProcess(SmartFileInfo $smartFileInfo): bool
    {
        return in_array($smartFileInfo->getExtension(), self::ALLOWED_FILE_EXTENSIONS, true);
    }

    public function allowedFileExtensions(): array
    {
        return self::ALLOWED_FILE_EXTENSIONS;
    }
}
