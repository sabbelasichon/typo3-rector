<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97449-PSR-14EventsForModifyingFlexFormParsing.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFlexFormParsingHooksToEventListenersRector\MigrateFlexFormParsingHooksToEventListenersRectorTest
 */
final class MigrateFlexFormParsingHooksToEventListenersRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var array<string, array{eventClass: string, methodName: string}>
     */
    private const METHOD_TO_EVENT_MAP = [
        'parseDataStructureByIdentifierPreProcess' => [
            'eventClass' => 'TYPO3\\CMS\\Core\\Configuration\\Event\\BeforeFlexFormDataStructureParsedEvent',
            'methodName' => 'handleBeforeFlexFormDataStructureParsed',
        ],
        'parseDataStructureByIdentifierPostProcess' => [
            'eventClass' => 'TYPO3\\CMS\\Core\\Configuration\\Event\\AfterFlexFormDataStructureParsedEvent',
            'methodName' => 'handleAfterFlexFormDataStructureParsed',
        ],
        'getDataStructureIdentifierPreProcess' => [
            'eventClass' => 'TYPO3\\CMS\\Core\\Configuration\\Event\\BeforeFlexFormDataStructureIdentifierInitializedEvent',
            'methodName' => 'handleBeforeFlexFormDataStructureIdentifierInitialized',
        ],
        'getDataStructureIdentifierPostProcess' => [
            'eventClass' => 'TYPO3\\CMS\\Core\\Configuration\\Event\\AfterFlexFormDataStructureIdentifierInitializedEvent',
            'methodName' => 'handleAfterFlexFormDataStructureIdentifierInitialized',
        ],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrate FlexForm parsing hooks to PSR-14 event listeners',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyFlexFormHook
{
    public function parseDataStructureByIdentifierPreProcess(array $identifier): array
    {
        if ($identifier['type'] === 'my_type') {
            return ['ROOT' => []];
        }
        return [];
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Configuration\Event\BeforeFlexFormDataStructureParsedEvent;

class MyFlexFormHook
{
    /**
     * @todo Register this listener in Configuration/Services.yaml. See https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97449-PSR-14EventsForModifyingFlexFormParsing.html
     */
    public function handleBeforeFlexFormDataStructureParsed(BeforeFlexFormDataStructureParsedEvent $event): void
    {
        if ($identifier['type'] === 'my_type') {
            return ['ROOT' => []];
        }
        return [];
    }
}
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
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $hasChanged = false;

        foreach (self::METHOD_TO_EVENT_MAP as $oldMethodName => $config) {
            $method = $node->getMethod($oldMethodName);
            if (! $method instanceof ClassMethod) {
                continue;
            }

            $this->migrateMethod($method, $config['eventClass'], $config['methodName']);
            $hasChanged = true;
        }

        return $hasChanged ? $node : null;
    }

    private function migrateMethod(ClassMethod $method, string $eventClass, string $newMethodName): void
    {
        // Rename the method
        $method->name = new Identifier($newMethodName);

        // Change return type to void
        $method->returnType = new Identifier('void');

        // Replace all parameters with single event parameter
        $method->params = [new Param(new Variable('event'), null, new FullyQualified($eventClass))];

        // Add docblock with todo
        $docComment = <<<'DOC'
/**
     * @todo Register this listener in Configuration/Services.yaml. See https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97449-PSR-14EventsForModifyingFlexFormParsing.html
     */
DOC;
        $method->setDocComment(new \PhpParser\Comment\Doc($docComment));
    }
}
