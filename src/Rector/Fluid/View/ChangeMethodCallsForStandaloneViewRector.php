<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Fluid\View;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\ConfiguredCodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Fluid\View\StandaloneView;

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

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Turns method call names to new ones.', [
            new ConfiguredCodeSample(
                <<<'PHP'
$someObject = new SomeExampleClass;
$someObject->oldMethod();
PHP
                ,
                <<<'PHP'
$someObject = new SomeExampleClass;
$someObject->newMethod();
PHP
                ,
                [
                    'SomeExampleClass' => [
                        'oldMethod' => 'newMethod',
                    ],
                ]
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
     * @param MethodCall|Node $node
     *
     * @return Node|null
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->oldToNewMethodsByClass as $type => $oldToNewMethods) {
            if (!$this->isObjectType($node, $type)) {
                continue;
            }

            foreach ($oldToNewMethods as $oldMethod => $newMethod) {
                if (!$this->isName($node, $oldMethod)) {
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
