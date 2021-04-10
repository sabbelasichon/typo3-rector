<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use Rector\Core\Rector\AbstractRector;
use Swift_Attachment;
use Swift_Image;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Mail\MailMessage;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/10.2/en-us/Changelog/10.0/Feature-88643-NewMailAPIBasedOnSymfonymailerAndSymfonymime.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v0\SwiftMailerBasedMailMessageToMailerBasedMessage\SwiftMailerBasedMailMessageToMailerBasedMessageRectorTest
 */
final class SwiftMailerBasedMailMessageToMailerBasedMessageRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(MailMessage::class)
        )) {
            return null;
        }

        if (! $this->isNames($node->name, ['setBody', 'addPart', 'attach', 'embed'])) {
            return null;
        }

        if ($this->isName($node->name, 'setBody')) {
            return $this->refactorMethodSetBody($node);
        }

        if ($this->isName($node->name, 'addPart')) {
            return $this->refactorMethodAddPart($node);
        }

        if ($this->isName($node->name, 'attach')) {
            return $this->refactorAttachMethod($node);
        }

        return $this->refactorEmbedMethod($node);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('New Mail API based on symfony/mailer and symfony/mime', [
            new CodeSample(<<<'CODE_SAMPLE'
use Swift_Attachment;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$mail = GeneralUtility::makeInstance(MailMessage::class);

$mail
    ->setSubject('Your subject')
    ->setFrom(['john@doe.com' => 'John Doe'])
    ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
    ->setBody('Here is the message itself')
    ->addPart('<p>Here is the message itself</p>', 'text/html')
    ->attach(Swift_Attachment::fromPath('my-document.pdf'))
    ->send();
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$mail = GeneralUtility::makeInstance(MailMessage::class);

$mail
    ->setSubject('Your subject')
    ->setFrom(['john@doe.com' => 'John Doe'])
    ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
    ->text('Here is the message itself')
    ->html('<p>Here is the message itself</p>')
    ->attachFromPath('my-document.pdf')
    ->send();
CODE_SAMPLE
        ),
        ]);
    }

    private function refactorMethodSetBody(MethodCall $node): ?MethodCall
    {
        $bodyType = $this->nodeTypeResolver->resolve($node->args[0]->value);
        $contentType = isset($node->args[1]) ? $this->valueResolver->getValue($node->args[1]->value) : null;

        if (! $bodyType instanceof StringType) {
            return null;
        }

        $methodIdentifier = 'text';
        if ('text/html' === $contentType) {
            $methodIdentifier = 'html';
        }

        if (null !== $contentType) {
            unset($node->args[1]);
        }

        $node->name = new Identifier($methodIdentifier);

        return $node;
    }

    private function refactorMethodAddPart(MethodCall $node): ?Node
    {
        $contentType = isset($node->args[1]) ? $this->valueResolver->getValue($node->args[1]->value) : null;

        $node->name = new Identifier('text');

        if (! is_string($contentType)) {
            return null;
        }

        unset($node->args[1]);

        if ('text/html' === $contentType) {
            $node->name = new Identifier('html');
            return $node;
        }

        return $node;
    }

    private function refactorAttachMethod(MethodCall $node): ?Node
    {
        $firstArgument = $node->args[0]->value;

        if (! $firstArgument instanceof StaticCall) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $firstArgument,
            new ObjectType(Swift_Attachment::class)
        )) {
            return null;
        }

        if (! $this->isName($firstArgument->name, 'fromPath')) {
            return null;
        }

        $node->name = new Identifier('attachFromPath');
        $node->args = $this->nodeFactory->createArgs($firstArgument->args);

        return $node;
    }

    private function refactorEmbedMethod(MethodCall $node): ?Node
    {
        $firstArgument = $node->args[0]->value;

        if (! $firstArgument instanceof StaticCall) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $firstArgument,
            new ObjectType(Swift_Image::class)
        )) {
            return null;
        }

        if (! $this->isName($firstArgument->name, 'fromPath')) {
            return null;
        }

        $node->name = new Identifier('embedFromPath');
        $node->args = $this->nodeFactory->createArgs($firstArgument->args);

        return $node;
    }
}
