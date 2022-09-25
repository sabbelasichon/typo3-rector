<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\General;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Ssch\TYPO3Rector\Helper\FilesFinder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @changelog https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ExtensionArchitecture/ConfigurationFiles/Index.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\General\ConvertImplicitVariablesToExplicitGlobalsRector\ConvertImplicitVariablesToExplicitGlobalsRectorTest
 */
final class ConvertImplicitVariablesToExplicitGlobalsRector extends AbstractRector
{
    /**
     * @readonly
     */
    private FilesFinder $filesFinder;

    public function __construct(FilesFinder $filesFinder)
    {
        $this->filesFinder = $filesFinder;
    }

    /**
     * @codeCoverageIgnore
     */
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

        if (null === $variableName) {
            return null;
        }

        return new ArrayDimFetch(new Variable('GLOBALS'), new String_($variableName));
    }

    private function shouldSkip(Variable $node): bool
    {
        if (! $this->isNames($node, ['TYPO3_CONF_VARS', 'TBE_MODULES', 'TCA'])) {
            return true;
        }

        if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
            return false;
        }

        $smartFileInfo = new SmartFileInfo($this->file->getFilePath());

        if ($this->filesFinder->isExtLocalConf($smartFileInfo)) {
            return false;
        }

        if ($this->filesFinder->isExtTables($smartFileInfo)) {
            return false;
        }

        return true;
    }
}
