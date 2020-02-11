<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Fluid\View;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Breaking-69863-RemovedDeprecatedCodeFromExtfluid.html
 */
final class ChangeMethodCallsForStandaloneViewRector extends AbstractRector
{
    /**
     * class => [
     *     oldMethod => newMethod
     * ].
     *
     * @var string[][]|mixed[][][]
     */
    private $oldToNewMethodsByClass = [
        StandaloneView::class => [
            'setLayoutRootPath' => 'setLayoutRootPaths',
            'getLayoutRootPath' => 'getLayoutRootPaths',
            'setPartialRootPath' => 'setPartialRootPaths',
            'getPartialRootPath' => 'getPartialRootPaths',
        ],
    ];

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Turns method call names to new ones.', [
            new CodeSample(
                <<<'PHP'
$someObject = new StandaloneView();
$someObject->setLayoutRootPath();
$someObject->getLayoutRootPath();
$someObject->setPartialRootPath();
$someObject->getPartialRootPath();
PHP
                ,
                <<<'PHP'
$someObject = new StandaloneView();
$someObject->setLayoutRootPaths();
$someObject->getLayoutRootPaths();
$someObject->setPartialRootPaths();
$someObject->getPartialRootPaths();
PHP
            ),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     *
     * @return Node|null
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->oldToNewMethodsByClass as $type => $oldToNewMethods) {
            if (!$this->isMethodStaticCallOrClassMethodObjectType($node, $type)) {
                continue;
            }

            foreach ($oldToNewMethods as $oldMethod => $newMethod) {
                if (!$this->isName($node->name, $oldMethod)) {
                    continue;
                }

                $methodName = $this->getName($node);

                switch ($methodName) {
                    // Wrap the first argument into an array
                    case 'setPartialRootPath':
                    case 'setLayoutRootPath':
                        $arguments = $node->args;
                        $firstArgument = array_shift($arguments);

                        $node->name = new Identifier($newMethod);
                        $node->args = [new Node\Arg(new Node\Expr\Array_([$firstArgument]))];

                        return $node;

                        break;
                    case 'getLayoutRootPath':
                    case 'getPartialRootPath':

                        $node->name = new Identifier($newMethod);

                        return new Node\Expr\FuncCall(new Node\Name('array_shift'), [new Node\Arg($node)]);
                        break;
                }
            }
        }

        return null;
    }
}
