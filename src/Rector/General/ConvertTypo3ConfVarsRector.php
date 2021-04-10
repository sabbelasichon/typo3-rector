<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\General;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Ssch\TYPO3Rector\Helper\FileHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @changelog https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ExtensionArchitecture/ConfigurationFiles/Index.html
 */
final class ConvertTypo3ConfVarsRector extends AbstractRector
{
    use FileHelperTrait;

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert $TYPO3_CONF_VARS to $GLOBALS[\'TYPO3_CONF_VARS\']', [
            new CodeSample(<<<'CODE_SAMPLE'
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['foo'] = 'FooBarBaz->handle';
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
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
        return [ArrayDimFetch::class];
    }

    /**
     * @param ArrayDimFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->var instanceof MethodCall) {
            return null;
        }

        if (! $this->isName($node->var, 'TYPO3_CONF_VARS')) {
            return null;
        }

        $fileInfo = $node->getAttribute(AttributeKey::FILE_INFO);
        if (! $fileInfo instanceof SmartFileInfo) {
            return null;
        }

        if (! $this->isExtLocalConf($fileInfo) && ! $this->isExtTables($fileInfo)) {
            return null;
        }

        $node->var = new ArrayDimFetch(new Variable('GLOBALS'), new String_('TYPO3_CONF_VARS'));

        return $node;
    }
}
