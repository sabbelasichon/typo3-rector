<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\NodeManipulator\ClassDependencyManipulator;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-108097-MailMessage-sendRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateRemovedMailMessageSendRector\MigrateRemovedMailMessageSendRectorTest
 */
final class MigrateRemovedMailMessageSendRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private ClassDependencyManipulator $classDependencyManipulator;

    public function __construct(ClassDependencyManipulator $classDependencyManipulator)
    {
        $this->classDependencyManipulator = $classDependencyManipulator;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrate removed `MailMessage->send()` to `MailerInterface->send()` via dependency injection',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Mail\MailMessage;

final readonly class MyController
{
    public function sendMail(): void
    {
        $email = new MailMessage();
        $email->subject('Some subject');
        $email->send();
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Mail\MailerInterface;

final readonly class MyController
{
    public function __construct(
        private MailerInterface $mailer
    ) {}

    public function sendMail(): void
    {
        $email = new MailMessage();
        $email->subject('Some subject');
        $this->mailer->send($email);
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
        $mailerPropertyName = 'mailer';

        $this->traverseNodesWithCallable($node->stmts, function (Node $subNode) use (
            &$hasChanged,
            $mailerPropertyName
        ) {
            if (! $subNode instanceof MethodCall) {
                return null;
            }

            if (! $this->isName($subNode->name, 'send')) {
                return null;
            }

            if (! $this->isObjectType($subNode->var, new ObjectType('TYPO3\CMS\Core\Mail\MailMessage'))) {
                return null;
            }

            $hasChanged = true;

            return $this->nodeFactory->createMethodCall(
                new PropertyFetch(new Variable('this'), $mailerPropertyName),
                'send',
                [$subNode->var]
            );
        });

        if ($hasChanged) {
            $this->addDependency($node, $mailerPropertyName, 'TYPO3\CMS\Core\Mail\MailerInterface');
            return $node;
        }

        return null;
    }

    /**
     * Adds a dependency using the ClassDependencyManipulator.
     */
    private function addDependency(Class_ $classNode, string $propertyName, string $className): void
    {
        if (\PHP_VERSION_ID >= PhpVersionFeature::READONLY_PROPERTY) {
            $flags = Modifiers::PRIVATE | Modifiers::READONLY;
        } else {
            $flags = Modifiers::PRIVATE;
        }

        $propertyMetadata = new PropertyMetadata($propertyName, new ObjectType($className), $flags);

        $this->classDependencyManipulator->addConstructorDependency($classNode, $propertyMetadata);
    }
}
