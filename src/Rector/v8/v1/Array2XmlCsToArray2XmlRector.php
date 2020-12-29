<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v1;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.1/Deprecation-75371-Array2xml_cs.html
 */
final class Array2XmlCsToArray2XmlRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, GeneralUtility::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'array2xml_cs')) {
            return null;
        }

        $node->name = new Identifier('array2xml');

        $args = $node->args;

        $array = isset($args[0]) ? $this->getValue($args[0]->value) : [];
        $doctag = isset($args[1]) ? $this->getValue($args[1]->value) : 'phparray';
        $options = isset($args[2]) ? $this->getValue($args[2]->value) : [];
        $charset = isset($args[3]) ? $this->getValue($args[3]->value) : 'utf-8';

        $node->args = $this->createArgs([$array, '', 0, $doctag, 0, $options]);

        return new Concat(
            new Concat(
                new Concat(
                    new Concat(
                        new String_('<?xml version="1.0" encoding="'),
                        $this->createFuncCall('htmlspecialchars', $this->createArgs([$charset]))
                    ),
                    new String_('" standalone="yes" ?>')
                ),
                new ConstFetch(new Name('LF'))
            ),
            $node
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('array2xml_cs to array2xml', [
            new CodeSample(<<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::array2xml_cs();
PHP
                , <<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::array2xml();
PHP
            ),
        ]);
    }
}
