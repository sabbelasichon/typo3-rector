<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Deprecation-73067-DeprecateGeneralUtilityrequireOnceAndGeneralUtilityrequireFile.html
 */
final class RequireMethodsToNativeFunctionsRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(GeneralUtility::class)
        )) {
            return null;
        }

        if (! $this->isNames($node->name, ['requireOnce', 'requireFile'])) {
            return null;
        }

        $file = $node->args[0]->value;

        if ($this->isName($node->name, 'requireFile')) {
            return new Include_($file, Include_::TYPE_REQUIRE);
        }

        return new Include_($file, Include_::TYPE_REQUIRE_ONCE);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor GeneralUtility::requireOnce and GeneralUtility::requireFile', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::requireOnce('somefile.php');
GeneralUtility::requireFile('some_other_file.php');
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
require_once 'somefile.php';
require 'some_other_file.php';
CODE_SAMPLE
            ),
        ]);
    }
}
