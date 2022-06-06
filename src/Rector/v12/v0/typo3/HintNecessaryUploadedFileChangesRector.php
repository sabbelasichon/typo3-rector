<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typo3;

use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\UnionType;
use Rector\Core\Console\Output\RectorOutputStyle;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-97214-UseUploadedFileObjectsInsteadOf_FILES.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\HintNecessaryUploadedFileChangesRector\HintNecessaryUploadedFileChangesRectorTest
 */
final class HintNecessaryUploadedFileChangesRector extends AbstractRector
{
    /**
     * @var string
     */
    private const MESSAGE = 'When extending the Core ResourceStorage, the addUploadedFile() method needs some adaptions. See Breaking-97214-UseUploadedFileObjectsInsteadOf_FILES.html for full migration advise';

    /**
     * @var string
     */
    private const COMMENT = '// FIXME: Rector: https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-97214-UseUploadedFileObjectsInsteadOf_FILES.html';

    public function __construct(
        private readonly RectorOutputStyle $rectorOutputStyle
    ) {
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $affectedMethod = $node->getMethod('addUploadedFile');

        if (! $affectedMethod instanceof ClassMethod) {
            return null;
        }

        $params = $affectedMethod->getParams();
        if ($params[0]->type instanceof UnionType) {
            return null;
        }

        if ($params[0]->type instanceof Identifier && 'array' === $params[0]->type->name) {
            $comments = $affectedMethod->getComments();
            $comments = array_filter(
                $comments,
                static fn (Comment $comment) => ! str_starts_with($comment->getText(), '// FIXME: Rector:')
            );

            $comments[] = new Comment(self::COMMENT);
            $affectedMethod->setAttribute('comments', $comments);

            $this->rectorOutputStyle->warning(self::MESSAGE);
        }

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        $comment = self::COMMENT;

        return new RuleDefinition('Add FIXME comment for necessary changes for addUploadedFile overrides', [
            new CodeSample(
                <<<'CODE_SAMPLE'
public function addUploadedFile(array $uploadedFileData)
{
}
CODE_SAMPLE
                ,
                <<<CODE_SAMPLE
{$comment}
public function addUploadedFile(array \$uploadedFileData)
{
}
CODE_SAMPLE
            ),
        ]);
    }

    private function shouldSkip(Class_ $class): bool
    {
        if (null === $class->extends) {
            return true;
        }

        return ! $this->isName($class->extends, 'TYPO3\CMS\Core\Resource\ResourceStorage');
    }
}
