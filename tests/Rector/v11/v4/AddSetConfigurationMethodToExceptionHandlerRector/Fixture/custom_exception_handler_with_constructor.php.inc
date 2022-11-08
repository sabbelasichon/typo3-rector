<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v4\AddSetConfigurationMethodToExceptionHandlerRector\Fixture;

use TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;
use TYPO3\CMS\Frontend\ContentObject\Exception\ExceptionHandlerInterface;

class MyCustomExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @var mixed[]
     */
    private array $typoscriptConfiguration;

    private string $foo;

    public function __construct(array $configurationParameter)
    {
        $this->foo = $this->extractFooFromConfiguration($configurationParameter);
        $this->initialize();
        $this->typoscriptConfiguration = $configurationParameter;
        $this->anotherWeirdCall($this->typoscriptConfiguration);
    }

    public function handle(\Exception $exception, AbstractContentObject $contentObject = null, $contentObjectConfiguration = [])
    {
        $bar = $this->foo;
        $foo = $this->typoscriptConfiguration['foo'];
    }

    private function extractFooFromConfiguration($configuration): string
    {
        return $configuration['foo'];
    }

    private function initialize(): void
    {
    }

    private function anotherWeirdCall($configuration): void
    {

    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v4\AddSetConfigurationMethodToExceptionHandlerRector\Fixture;

use TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;
use TYPO3\CMS\Frontend\ContentObject\Exception\ExceptionHandlerInterface;

class MyCustomExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @var mixed[]
     */
    private array $typoscriptConfiguration;

    private string $foo;

    public function __construct()
    {
    }

    public function handle(\Exception $exception, AbstractContentObject $contentObject = null, $contentObjectConfiguration = [])
    {
        $bar = $this->foo;
        $foo = $this->typoscriptConfiguration['foo'];
    }

    private function extractFooFromConfiguration($configuration): string
    {
        return $configuration['foo'];
    }

    private function initialize(): void
    {
    }

    private function anotherWeirdCall($configuration): void
    {

    }

    public function setConfiguration(array $configuration): void
    {
        $this->foo = $this->extractFooFromConfiguration($configuration);
        $this->initialize();
        $this->typoscriptConfiguration = $configuration;
        $this->anotherWeirdCall($this->typoscriptConfiguration);
    }
}

?>
