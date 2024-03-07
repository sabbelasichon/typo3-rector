<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.3/Deprecation-94316-DeprecatedHTTPHeaderManipulatingMethodsFromHttpUtility.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v3\MigrateHttpUtilityRedirectRector\MigrateHttpUtilityRedirectRectorTest
 */
final class MigrateHttpUtilityRedirectRector extends AbstractRector
{
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

$response = GeneralUtility::makeInstance(ResponseFactoryInterface::class)
    ->createResponse(303)
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
            new ObjectType(HttpUtility::class)
        )) {
            return null;
        }

        if (! $this->isName($staticCall->name, 'redirect')) {
            return null;
        }

        $parameters = $staticCall->args;
        $target = $parameters[0];
        $code = $parameters[1] ?? $this->nodeFactory->createClassConstFetch(HttpUtility::class, 'HTTP_STATUS_303');

        $newNode = $this->nodeFactory->createStaticCall(
            GeneralUtility::class,
            'makeInstance',
            [$this->nodeFactory->createClassConstReference('Psr\Http\Message\ResponseFactoryInterface')]
        );
        $createResponseCallNode = $this->nodeFactory->createMethodCall($newNode, 'createResponse', [$code]);
        $withHeaderCallNode = $this->nodeFactory->createMethodCall($createResponseCallNode, 'withAddedHeader', [
            'location',
            $target,
        ]);

        $responseVariable = new Node\Expr\Variable('response');
        $assignment = new Expression(new Node\Expr\Assign($responseVariable, $withHeaderCallNode));

        $exception =
            new Node\Stmt\Throw_(
                new Node\Expr\New_(
                    new Node\Name\FullyQualified(PropagateResponseException::class),
                    [new Node\Arg($responseVariable)]
                )
            );

        return [new Node\Stmt\Nop(), $assignment, $exception];
    }
}
