<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\CodeQuality\General;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeCombinator;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\NodeManipulator\ClassDependencyManipulator;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Ssch\TYPO3Rector\Contract\NoChangelogRequiredInterface;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\GeneralUtilityMakeInstanceToConstructorPropertyRectorTest
 */
final class GeneralUtilityMakeInstanceToConstructorPropertyRector extends AbstractRector implements ConfigurableRectorInterface, NoChangelogRequiredInterface, DocumentedRuleInterface
{
    /**
     * @var string
     */
    public const ALLOWED_CLASSES = 'allowed_classes';

    /**
     * @var string[]
     */
    private array $allowedClasses = [
        'TYPO3\CMS\Backend\CodeEditor\CodeEditor',
        'TYPO3\CMS\Backend\CodeEditor\Registry\AddonRegistry',
        'TYPO3\CMS\Backend\CodeEditor\Registry\ModeRegistry',
        'TYPO3\CMS\Backend\Routing\UriBuilder',
        'TYPO3\CMS\Backend\View\BackendLayout\DataProviderCollection',
        'TYPO3\CMS\Backend\View\BackendLayoutView',
        'TYPO3\CMS\Beuser\Domain\Repository\BackendUserGroupRepository',
        'TYPO3\CMS\Beuser\Domain\Repository\BackendUserRepository',
        'TYPO3\CMS\Beuser\Domain\Repository\FileMountRepository',
        'TYPO3\CMS\Core\Cache\CacheManager',
        'TYPO3\CMS\Core\Composer\PackageArtifactBuilder',
        'TYPO3\CMS\Core\Configuration\Features',
        'TYPO3\CMS\Core\Console\CommandRegistry',
        'TYPO3\CMS\Core\Context\Context',
        'TYPO3\CMS\Core\Crypto\HashService',
        'TYPO3\CMS\Core\Database\ConnectionPool',
        'TYPO3\CMS\Core\Error\DebugExceptionHandler',
        'TYPO3\CMS\Core\Error\ProductionExceptionHandler',
        'TYPO3\CMS\Core\EventDispatcher\EventDispatcher',
        'TYPO3\CMS\Core\Html\DefaultSanitizerBuilder',
        'TYPO3\CMS\Core\Imaging\IconFactory',
        'TYPO3\CMS\Core\Imaging\IconRegistry',
        'TYPO3\CMS\Core\LinkHandling\LinkService',
        'TYPO3\CMS\Core\Localization\LanguageServiceFactory',
        'TYPO3\CMS\Core\Localization\Locales',
        'TYPO3\CMS\Core\Locking\LockFactory',
        'TYPO3\CMS\Core\Log\LogManager',
        'TYPO3\CMS\Core\Mail\MemorySpool',
        'TYPO3\CMS\Core\Mail\TransportFactory',
        'TYPO3\CMS\Core\Messaging\FlashMessageService',
        'TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry',
        'TYPO3\CMS\Core\Package\FailsafePackageManager',
        'TYPO3\CMS\Core\Package\PackageManager',
        'TYPO3\CMS\Core\Package\UnitTestPackageManager',
        'TYPO3\CMS\Core\Page\AssetCollector',
        'TYPO3\CMS\Core\Page\ImportMapFactory',
        'TYPO3\CMS\Core\Page\PageRenderer',
        'TYPO3\CMS\Core\PageTitle\PageTitleProviderManager',
        'TYPO3\CMS\Core\PageTitle\RecordPageTitleProvider',
        'TYPO3\CMS\Core\PageTitle\RecordTitleProvider',
        'TYPO3\CMS\Core\Registry',
        'TYPO3\CMS\Core\Resource\Collection\FileCollectionRegistry',
        'TYPO3\CMS\Core\Resource\Driver\DriverRegistry',
        'TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry',
        'TYPO3\CMS\Core\Resource\Processing\TaskTypeRegistry',
        'TYPO3\CMS\Core\Resource\Rendering\AudioTagRenderer',
        'TYPO3\CMS\Core\Resource\Rendering\RendererRegistry',
        'TYPO3\CMS\Core\Resource\Rendering\VideoTagRenderer',
        'TYPO3\CMS\Core\Resource\Rendering\VimeoRenderer',
        'TYPO3\CMS\Core\Resource\Rendering\YouTubeRenderer',
        'TYPO3\CMS\Core\Resource\ResourceFactory',
        'TYPO3\CMS\Core\Resource\TextExtraction\TextExtractorRegistry',
        'TYPO3\CMS\Core\Routing\SiteMatcher',
        'TYPO3\CMS\Core\Schema\TcaSchemaFactory',
        'TYPO3\CMS\Core\Service\Archive\ZipService',
        'TYPO3\CMS\Core\Service\DependencyOrderingService',
        'TYPO3\CMS\Core\Service\FlexFormService',
        'TYPO3\CMS\Core\Service\MarkerBasedTemplateService',
        'TYPO3\CMS\Core\Session\SessionManager',
        'TYPO3\CMS\Core\Site\SiteFinder',
        'TYPO3\CMS\Core\TimeTracker\TimeTracker',
        'TYPO3\CMS\Extbase\Configuration\ConfigurationManager',
        'TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface',
        'TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfigurationService',
        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
        'TYPO3\CMS\Extbase\Persistence\ClassesConfiguration',
        'TYPO3\CMS\Extbase\Persistence\Generic\Mapper\ColumnMapFactory',
        'TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager',
        'TYPO3\CMS\Extbase\Persistence\Generic\Qom\QueryObjectModelFactory',
        'TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbBackend',
        'TYPO3\CMS\Extbase\Persistence\Repository',
        'TYPO3\CMS\Extbase\Property\PropertyMapper',
        'TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationBuilder',
        'TYPO3\CMS\Extbase\Property\TypeConverter\ArrayConverter',
        'TYPO3\CMS\Extbase\Property\TypeConverter\BooleanConverter',
        'TYPO3\CMS\Extbase\Property\TypeConverter\CoreTypeConverter',
        'TYPO3\CMS\Extbase\Property\TypeConverter\CountryConverter',
        'TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter',
        'TYPO3\CMS\Extbase\Property\TypeConverter\EnumConverter',
        'TYPO3\CMS\Extbase\Property\TypeConverter\FileConverter',
        'TYPO3\CMS\Extbase\Property\TypeConverter\FileReferenceConverter',
        'TYPO3\CMS\Extbase\Property\TypeConverter\FloatConverter',
        'TYPO3\CMS\Extbase\Property\TypeConverter\FolderConverter',
        'TYPO3\CMS\Extbase\Property\TypeConverter\IntegerConverter',
        'TYPO3\CMS\Extbase\Property\TypeConverter\ObjectConverter',
        'TYPO3\CMS\Extbase\Property\TypeConverter\ObjectStorageConverter',
        'TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter',
        'TYPO3\CMS\Extbase\Property\TypeConverter\StringConverter',
        'TYPO3\CMS\Extbase\Reflection\ReflectionService',
        'TYPO3\CMS\Extbase\Service\CacheService',
        'TYPO3\CMS\Extbase\Service\ExtensionService',
        'TYPO3\CMS\Extbase\Service\FileHandlingService',
        'TYPO3\CMS\Extbase\Service\ImageService',
        'TYPO3\CMS\Extbase\Validation\ValidatorResolver',
        'TYPO3\CMS\Frontend\ContentObject\Menu\MenuContentObjectFactory',
        'TYPO3\CMS\Frontend\Typolink\PageLinkBuilder',
        'TYPO3\CMS\Scheduler\Scheduler',
        'TYPO3\CMS\Seo\PageTitle\SeoTitlePageTitleProvider',
        'TYPO3\CMS\Workspaces\Service\Dependency\CollectionService',
        'TYPO3\CMS\Workspaces\Service\HistoryService',
    ];

    /**
     * @readonly
     */
    private ClassDependencyManipulator $classDependencyManipulator;

    /**
     * @readonly
     */
    private ReflectionProvider $reflectionProvider;

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(
        ClassDependencyManipulator $classDependencyManipulator,
        ReflectionProvider $reflectionProvider,
        ValueResolver $valueResolver
    ) {
        $this->classDependencyManipulator = $classDependencyManipulator;
        $this->reflectionProvider = $reflectionProvider;
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Move GeneralUtility::makeInstance calls to constructor injection',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Service
{
    public function myMethod(): void
    {
        GeneralUtility::makeInstance(Context::class)->getAspect('frontend.user');
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Context\Context;

class Service
{
    private Context $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function myMethod(): void
    {
        $this->context->getAspect('frontend.user');
    }
}
CODE_SAMPLE
                    ,
                    [
                        self::ALLOWED_CLASSES => ['TYPO3\CMS\Core\Context\Context'],
                    ]
                ),
            ]
        );
    }

    /**
     * @return class-string[]
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

        $hasChanged = false;

        foreach ($node->getMethods() as $classMethod) {
            // If the method is static, we cannot perform DI with `$this`, so we skip this method entirely.
            // Also skip if we are in the constructor
            if ($classMethod->isStatic() || $this->isName($classMethod, '__construct')) {
                continue;
            }

            if ($classMethod->stmts === null) {
                continue;
            }

            if ($classMethod->stmts === []) {
                continue;
            }

            $this->traverseNodesWithCallable($classMethod->stmts, function (Node $subNode) use ($node, &$hasChanged) {
                if (! $subNode instanceof StaticCall) {
                    return null;
                }

                if (! $this->isName($subNode->name, 'makeInstance')) {
                    return null;
                }

                if (! $this->isObjectType($subNode->class, new ObjectType('TYPO3\CMS\Core\Utility\GeneralUtility'))) {
                    return null;
                }

                if (! isset($subNode->args[0])) {
                    return null;
                }

                if (isset($subNode->args[1])) {
                    // Skip if there are constructor arguments
                    return null;
                }

                $className = $this->valueResolver->getValue($subNode->args[0]->value);
                if (! is_string($className)) {
                    return null;
                }

                if ($this->allowedClasses !== [] && ! in_array($className, $this->allowedClasses, true)) {
                    return null;
                }

                // Derive a property name from the class name (e.g., Context -> $context)
                $shortClassName = $this->nodeNameResolver->getShortName($className);
                $propertyName = lcfirst($shortClassName);

                if (\PHP_VERSION_ID >= PhpVersionFeature::READONLY_PROPERTY) {
                    $flags = Modifiers::PRIVATE & Modifiers::READONLY;
                } else {
                    $flags = Modifiers::PRIVATE;
                }

                $propertyMetadata = new PropertyMetadata($propertyName, new ObjectType($className), $flags);

                $this->classDependencyManipulator->addConstructorDependency($node, $propertyMetadata);

                $hasChanged = true;

                return $this->nodeFactory->createPropertyFetch('this', $propertyName);
            });
        }

        return $hasChanged ? $node : null;
    }

    public function configure(array $configuration): void
    {
        $allowedClasses = $configuration[self::ALLOWED_CLASSES] ?? [];
        Assert::isArray($allowedClasses);
        Assert::allString($allowedClasses);

        $this->allowedClasses = $allowedClasses;
    }

    private function shouldSkip(Class_ $classNode): bool
    {
        if ($classNode->isAbstract() || $classNode->isAnonymous()) {
            return true;
        }

        $className = $this->getName($classNode);
        if ($className === null) {
            return true;
        }

        // Check if the class is known to PHPStan's reflection
        if (! $this->reflectionProvider->hasClass($className)) {
            // If class is not known to reflection, better to skip it.
            return true;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        // Traverse the class hierarchy (current class and its parents)
        $currentClassReflection = $classReflection;
        do {
            // Check if the current class in the hierarchy has its OWN constructor
            if ($currentClassReflection->hasNativeMethod('__construct')) {
                $constructorReflection = $currentClassReflection->getNativeMethod('__construct');

                // A constructor can have multiple variants (e.g. from phpdoc). We check the first one.
                $parametersAcceptor = $constructorReflection->getVariants()[0] ?? null;
                if ($parametersAcceptor === null) {
                    continue;
                }

                foreach ($parametersAcceptor->getParameters() as $parameterReflection) {
                    $paramType = $parameterReflection->getType();
                    // Remove null from the type before checking if it's a scalar or array
                    $typeWithoutNull = TypeCombinator::removeNull($paramType);
                    if ($typeWithoutNull->isScalar()->yes() || $typeWithoutNull->isArray()->yes()) {
                        return true;
                    }
                }
            }

            $currentClassReflection = $currentClassReflection->getParentClass();
        } while ($currentClassReflection instanceof ClassReflection);

        // No constructor found in the current class or any of its parents
        return false;
    }
}
