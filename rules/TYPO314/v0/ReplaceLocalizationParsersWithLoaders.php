<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-107436-LocalizationParsers.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\ReplaceLocalizationParsersWitHLoaders\ReplaceLocalizationParsersWitHLoadersTest
 */
final class ReplaceLocalizationParsersWithLoaders extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace localization parsers with loaders',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['SYS']['lang']['parser']['xlf'] = \TYPO3\CMS\Core\Localization\Parser\XliffParser::class;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['LANG']['loader']['xlf'] = \TYPO3\CMS\Core\Localization\Loader\XliffLoader::class;
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isTargetArrayDimFetch($node->var)) {
            return null;
        }

        if (! $node->expr instanceof ClassConstFetch || ! $this->isName($node->expr->name, 'class')) {
            return null;
        }

        if (! $this->isObjectType(
            $node->expr->class,
            new ObjectType('TYPO3\CMS\Core\Localization\Parser\XliffParser')
        )) {
            return null;
        }

        $this->refactorConfigurationPath($node);
        $this->refactorClassName($node->expr);

        return $node;
    }

    private function isTargetArrayDimFetch(Node $node): bool
    {
        if (! $node instanceof ArrayDimFetch) {
            return false;
        }

        $arrayDimFetch = $node;
        $keys = [];
        while ($arrayDimFetch instanceof ArrayDimFetch) {
            if ($arrayDimFetch->dim instanceof String_) {
                $keys[] = $arrayDimFetch->dim->value;
            } else {
                return false;
            }

            $arrayDimFetch = $arrayDimFetch->var;
        }

        if (! $this->isName($arrayDimFetch, 'GLOBALS')) {
            return false;
        }

        $keys = array_reverse($keys);

        return ($keys[0] ?? '') === 'TYPO3_CONF_VARS'
            && ($keys[1] ?? '') === 'SYS'
            && ($keys[2] ?? '') === 'lang'
            && ($keys[3] ?? '') === 'parser';
    }

    private function refactorConfigurationPath(Assign $node): void
    {
        /** @var ArrayDimFetch $originalVar */
        $originalVar = $node->var;

        // Get the file type key (e.g., 'xlf') from the innermost dimension
        $fileTypeKey = $originalVar->dim;

        // Find the root variable (should be $GLOBALS)
        $rootVar = $originalVar;
        while ($rootVar instanceof ArrayDimFetch) {
            $rootVar = $rootVar->var;
        }

        // Rebuild the entire array access path correctly
        $newPath = $this->buildNewPath($rootVar, $fileTypeKey);
        $node->var = $newPath;
    }

    private function buildNewPath(Expr $rootVar, ?Expr $fileTypeKey): ArrayDimFetch
    {
        $path = new ArrayDimFetch($rootVar, new String_('TYPO3_CONF_VARS'));
        $path = new ArrayDimFetch($path, new String_('LANG'));
        $path = new ArrayDimFetch($path, new String_('loader'));
        return new ArrayDimFetch($path, $fileTypeKey);
    }

    private function refactorClassName(ClassConstFetch $classConstFetch): void
    {
        $classConstFetch->class = new FullyQualified('TYPO3\CMS\Core\Localization\Loader\XliffLoader');
    }
}
