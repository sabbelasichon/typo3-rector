<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v1;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symfony\Component\Mime\Address;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MailUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.1/Deprecation-88850-ContentObjectRendererSendNotifyEmail.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v1\SendNotifyEmailToMailApi\SendNotifyEmailToMailApiRectorTest
 */
final class SendNotifyEmailToMailApiRector extends AbstractRector
{
    /**
     * @var string
     */
    private const MAIL = 'mail';

    /**
     * @var string
     */
    private const MESSAGE = 'message';

    /**
     * @var string
     */
    private const TRIM = 'trim';

    /**
     * @var string
     */
    private const SENDER_ADDRESS = 'senderAddress';

    /**
     * @var string
     */
    private const MESSAGE_PARTS = 'messageParts';

    /**
     * @var string
     */
    private const SUBJECT = 'subject';

    /**
     * @var string
     */
    private const PARSED_RECIPIENTS = 'parsedRecipients';

    /**
     * @var string
     */
    private const SUCCESS = 'success';

    /**
     * @var string
     */
    private const PARSED_REPLY_TO = 'parsedReplyTo';

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, ContentObjectRenderer::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'sendNotifyEmail')) {
            return null;
        }

        $currentStmts = $node->getAttribute(AttributeKey::CURRENT_STATEMENT);
        $positionNode = $currentStmts ?? $node;

        $this->addNodeBeforeNode($this->initializeSuccessVariable(), $positionNode);
        $this->addNodeBeforeNode($this->initializeMailClass(), $positionNode);
        $this->addNodeBeforeNode($this->trimMessage($node), $positionNode);
        $this->addNodeBeforeNode($this->trimSenderName($node), $positionNode);
        $this->addNodeBeforeNode($this->trimSenderAddress($node), $positionNode);
        $this->addNodeBeforeNode($this->ifSenderAddress(), $positionNode);

        $replyTo = isset($node->args[5]) ? $node->args[5]->value : null;
        if (null !== $replyTo) {
            $this->addNodeBeforeNode($this->parsedReplyTo($replyTo), $positionNode);
            $this->addNodeBeforeNode($this->methodReplyTo(), $positionNode);
        }
        $ifMessageNotEmpty = $this->messageNotEmpty();
        $ifMessageNotEmpty->stmts[] = $this->messageParts();
        $ifMessageNotEmpty->stmts[] = $this->subjectFromMessageParts();
        $ifMessageNotEmpty->stmts[] = $this->bodyFromMessageParts();
        $ifMessageNotEmpty->stmts[] = $this->parsedRecipients($node);
        $ifMessageNotEmpty->stmts[] = $this->ifParsedRecipients();
        $ifMessageNotEmpty->stmts[] = $this->createSuccessTrue();

        $this->addNodeBeforeNode($ifMessageNotEmpty, $positionNode);

        return new Variable(self::SUCCESS);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor ContentObjectRenderer::sendNotifyEmail to MailMessage-API', [
            new CodeSample(<<<'PHP'
$GLOBALS['TSFE']->cObj->sendNotifyEmail("Subject\nMessage", 'max.mustermann@domain.com', 'max.mustermann@domain.com', 'max.mustermann@domain.com');
PHP
                , <<<'PHP'
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MailUtility;$success = false;

$mail = GeneralUtility::makeInstance(MailMessage::class);
$message = trim("Subject\nMessage");
$senderName = trim(null);
$senderAddress = trim('max.mustermann@domain.com');

if ($senderAddress !== '') {
    $mail->from(new Address($senderAddress, $senderName));
}

if ($message !== '') {
    $messageParts = explode(LF, $message, 2);
    $subject = trim($messageParts[0]);
    $plainMessage = trim($messageParts[1]);
    $parsedRecipients = MailUtility::parseAddresses('max.mustermann@domain.com');
    if (!empty($parsedRecipients)) {
        $mail->to(...$parsedRecipients)->subject($subject)->text($plainMessage);
        $mail->send();
    }
    $success = true;
}
PHP
            ),
        ]);
    }

    private function initializeSuccessVariable(): Node
    {
        return new Expression(new Assign(new Variable(self::SUCCESS), $this->nodeFactory->createFalse()));
    }

    private function initializeMailClass(): Node
    {
        return new Expression(new Assign(new Variable(self::MAIL), $this->nodeFactory->createStaticCall(
            GeneralUtility::class,
            'makeInstance',
            [$this->nodeFactory->createClassConstReference(MailMessage::class)]
        )));
    }

    private function trimMessage(MethodCall $node): Node
    {
        return new Assign(new Variable(self::MESSAGE), $this->nodeFactory->createFuncCall(
            self::TRIM,
            [$node->args[0]]
        ));
    }

    private function trimSenderName(MethodCall $methodCall): Node
    {
        return new Expression(new Assign(new Variable('senderName'), $this->nodeFactory->createFuncCall(
            self::TRIM,
            [$methodCall->args[4] ?? new Expr\ConstFetch(new Name('null'))]
        )));
    }

    private function trimSenderAddress(MethodCall $node): Node
    {
        return new Expression(new Assign(new Variable(self::SENDER_ADDRESS), $this->nodeFactory->createFuncCall(
            self::TRIM,
            [$node->args[3]]
        )));
    }

    private function mailFromMethodCall(): MethodCall
    {
        return $this->nodeFactory->createMethodCall(self::MAIL, 'from', [
            new New_(new FullyQualified(Address::class), [
                $this->nodeFactory->createArg(new Variable(self::SENDER_ADDRESS)),
                $this->nodeFactory->createArg(new Variable('senderName')),
            ]),
        ]);
    }

    private function ifSenderAddress(): Node
    {
        $mailFromMethodCall = $this->mailFromMethodCall();
        $ifSenderName = new If_(new NotIdentical(new Variable(self::SENDER_ADDRESS), new String_('')));
        $ifSenderName->stmts[0] = new Expression($mailFromMethodCall);

        return $ifSenderName;
    }

    private function messageNotEmpty(): If_
    {
        return new If_(new NotIdentical(new Variable(self::MESSAGE), new String_('')));
    }

    private function messageParts(): Expression
    {
        return new Expression(new Assign(new Variable(self::MESSAGE_PARTS), $this->nodeFactory->createFuncCall(
            'explode',
            [new ConstFetch(new Name('LF')), new Variable(self::MESSAGE), new LNumber(2)]
        )));
    }

    private function subjectFromMessageParts(): Expression
    {
        return new Expression(new Assign(new Variable(self::SUBJECT), $this->nodeFactory->createFuncCall(self::TRIM, [
            new ArrayDimFetch(new Variable(self::MESSAGE_PARTS), new LNumber(0)),
        ])));
    }

    private function bodyFromMessageParts(): Expression
    {
        return new Expression(new Assign(new Variable('plainMessage'), $this->nodeFactory->createFuncCall(self::TRIM, [
            new ArrayDimFetch(new Variable(self::MESSAGE_PARTS), new LNumber(1)),
        ])));
    }

    private function parsedRecipients(MethodCall $node): Expression
    {
        return new Expression(
            new Assign(new Variable(self::PARSED_RECIPIENTS),
                $this->nodeFactory->createStaticCall(MailUtility::class, 'parseAddresses', [$node->args[1]]))
        );
    }

    private function ifParsedRecipients(): If_
    {
        $ifParsedRecipients = new If_(new BooleanNot(new Empty_(new Variable(self::PARSED_RECIPIENTS))));
        $ifParsedRecipients->stmts[] = new Expression($this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall(
                $this->nodeFactory->createMethodCall(
                    self::MAIL,
                    'to',
                    [new Arg(new Variable(self::PARSED_RECIPIENTS), false, true)]
                ),
                self::SUBJECT,
                [new Variable(self::SUBJECT)]
            ),
            'text',
            [new Variable('plainMessage')]
        ));
        $ifParsedRecipients->stmts[] = new Expression($this->nodeFactory->createMethodCall(self::MAIL, 'send'));

        return $ifParsedRecipients;
    }

    private function createSuccessTrue(): Expression
    {
        return new Expression(new Assign(new Variable(self::SUCCESS), $this->nodeFactory->createTrue()));
    }

    private function parsedReplyTo(Expr $replyTo): Node
    {
        return new Expression(new Assign(new Variable(self::PARSED_REPLY_TO), $this->nodeFactory->createStaticCall(
            MailUtility::class,
            'parseAddresses',
            [$replyTo]
        )));
    }

    private function methodReplyTo(): Node
    {
        $ifNode = new If_(new BooleanNot(new Empty_(new Variable(self::PARSED_REPLY_TO))));
        $ifNode->stmts[] = new Expression($this->nodeFactory->createMethodCall(
            self::MAIL,
            'setReplyTo',
            [new Variable(self::PARSED_REPLY_TO)]
        ));

        return $ifNode;
    }
}
