<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\CodeQuality\General;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use Rector\Rector\AbstractRector;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;
use Ssch\TYPO3Rector\NodeFactory\Typo3GlobalsFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ExtensionArchitecture/BestPractises/ConfigurationFiles.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\ConvertImplicitVariablesToExplicitGlobalsRector\ConvertImplicitVariablesToExplicitGlobalsRectorTest
 */
final class ConvertImplicitVariablesToExplicitGlobalsRector extends AbstractRector
{
    /**
     * @readonly
     */
    private FilesFinder $filesFinder;

    /**
     * @readonly
     */
    private Typo3GlobalsFactory $typo3GlobalsFactory;

    public function __construct(FilesFinder $filesFinder, Typo3GlobalsFactory $typo3GlobalsFactory)
    {
        $this->filesFinder = $filesFinder;
        $this->typo3GlobalsFactory = $typo3GlobalsFactory;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert $TYPO3_CONF_VARS to $GLOBALS[\'TYPO3_CONF_VARS\']', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['foo'] = 'FooBarBaz->handle';
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['foo'] = 'FooBarBaz->handle';
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Variable::class];
    }

    /**
     * @param Variable $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $variableName = $this->getName($node);

        if ($variableName === null) {
            return null;
        }

        return $this->typo3GlobalsFactory->create($variableName);
    }

    private function shouldSkip(Variable $node): bool
    {
        if (! $this->isNames($node, ['TYPO3_CONF_VARS', 'TBE_MODULES', 'TCA'])) {
            return true;
        }

        if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
            return false;
        }

        if ($this->filesFinder->isExtLocalConf($this->file->getFilePath())) {
            return false;
        }

        return ! $this->filesFinder->isExtTables($this->file->getFilePath());
    }
}
