<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v7;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.7/Deprecation-80514-GraphicalFunctions-tempPathAndCreateTempSubDir.html
 */
final class RefactorGraphicalFunctionsTempPathAndCreateTemSubDirRector extends AbstractRector
{
    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, PropertyFetch::class];
    }

    /**
     * @param MethodCall|PropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isObjectType($node->var, GraphicalFunctions::class)) {
            return null;
        }

        if (null === $node->name) {
            return null;
        }

        if ($this->isName($node->name, 'createTempSubDir')) {
            if (! isset($node->args)) {
                return null;
            }

            /** @var Arg[] $args */
            $args = $node->args;
            $firstArgument = array_shift($args);

            if (null === $firstArgument) {
                return null;
            }

            //if (null === $firstArgument->value) {
            //    return null;
            //}

            if (null === $this->getValue($firstArgument->value)) {
                return null;
            }

            $param = new String_('typo3temp/' . $this->getValue($firstArgument->value));

            return $this->createStaticCall(GeneralUtility::class, 'mkdir_deep', [
                new Concat(new ConstFetch(new Name('PATH_site')), $param),
            ]);
        }

        if (! isset($node->name->name)) {
            return null;
        }

        if ('tempPath' === $node->name->name) {
            return new String_('typo3temp/');
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Refactor tempPath() and createTempSubDir on GraphicalFunctions', [
            new CodeSample(<<<'PHP'
$graphicalFunctions = GeneralUtility::makeInstance(GraphicalFunctions::class);
$graphicalFunctions->createTempSubDir('var/transient/');
return $graphicalFunctions->tempPath . 'var/transient/';
PHP
            , <<<'PHP'
$graphicalFunctions = GeneralUtility::makeInstance(GraphicalFunctions::class);
GeneralUtility::mkdir_deep(PATH_site . 'typo3temp/var/transient/');
return 'typo3temp/' . 'var/transient/';
PHP
        ),
        ]);
    }
}
