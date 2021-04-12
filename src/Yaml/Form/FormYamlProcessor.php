<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Yaml\Form;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\NonPhpFile\NonPhpFileChange;
use Ssch\TYPO3Rector\Yaml\Form\Transformer\FormYamlTransformer;
use Symfony\Component\Yaml\Yaml;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @see \Ssch\TYPO3Rector\Tests\Yaml\Form\FormYamlProcessorTest
 */
final class FormYamlProcessor implements FileProcessorInterface
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

    public function process(SmartFileInfo $smartFileInfo): ?NonPhpFileChange
    {
        $yaml = Yaml::parseFile($smartFileInfo->getRealPath());

        if (! is_array($yaml)) {
            return null;
        }

        foreach ($this->transformer as $transformer) {
            $yaml = $transformer->transform($yaml);
        }

        return new NonPhpFileChange($smartFileInfo->getContents(), Yaml::dump($yaml, 99, 2));
    }

    public function supports(SmartFileInfo $smartFileInfo): bool
    {
        if ([] === $this->transformer) {
            return false;
        }

        return in_array($smartFileInfo->getExtension(), self::ALLOWED_FILE_EXTENSIONS, true);
    }

    public function getSupportedFileExtensions(): array
    {
        return self::ALLOWED_FILE_EXTENSIONS;
    }
}
