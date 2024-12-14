<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v3;

use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Nop;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.3/Deprecation-94316-DeprecatedHTTPHeaderManipulatingMethodsFromHttpUtility.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v3\MigrateHttpUtilityRedirectRector\MigrateHttpUtilityRedirectRectorTest
 */
final class MigrateHttpUtilityRedirectRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private BuilderFactory $builderFactory;

    public function __construct(BuilderFactory $builderFactory)
    {
        $this->builderFactory = $builderFactory;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate HttpUtilty::redirect() to responseFactory', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\HttpUtility;

HttpUtility::redirect('https://example.com', HttpUtility::HTTP_STATUS_303);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use Psr\Http\Message\ResponseFactoryInterface;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;

$response = GeneralUtility::makeInstance(ResponseFactoryInterface::class)
    ->createResponse(HttpUtility::HTTP_STATUS_303)
    ->withAddedHeader('location', 'https://example.com');
throw new PropagateResponseException($response);
CODE_SAMPLE
        ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     * @return Node[]|null
     */
    public function refactor(Node $node): ?array
    {
        $staticCall = $node->expr;
        if (! $staticCall instanceof StaticCall) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticCall,
            new ObjectType('TYPO3\CMS\Core\Utility\HttpUtility')
        )) {
            return null;
        }

        if (! $this->isName($staticCall->name, 'redirect')) {
            return null;
        }

        $target = $staticCall->args[0];
        $httpStatusCode = $staticCall->args[1] ?? $this->nodeFactory->createClassConstFetch(
            'TYPO3\CMS\Core\Utility\HttpUtility',
            'HTTP_STATUS_303'
        );

        $createResponseCallNode = $this->nodeFactory->createMethodCall(
            $this->createResponseFactory(),
            'createResponse',
            [$httpStatusCode]
        );
        $withHeaderCallNode = $this->nodeFactory->createMethodCall($createResponseCallNode, 'withAddedHeader', [
            'location',
            $target,
        ]);

        $responseVariable = new Variable('response');
        $assignment = new Expression(new Assign($responseVariable, $withHeaderCallNode));

        $exception = new Expression(new Throw_($this->builderFactory->new(
            '\\TYPO3\\CMS\\Core\\Http\\PropagateResponseException',
            [$responseVariable]
        )));

        return [new Nop(), $assignment, $exception];
    }

    private function createResponseFactory(): StaticCall
    {
        return $this->nodeFactory->createStaticCall(
            'TYPO3\CMS\Core\Utility\GeneralUtility',
            'makeInstance',
            [$this->nodeFactory->createClassConstReference('Psr\\Http\\Message\\ResponseFactoryInterface')]
        );
    }
}
