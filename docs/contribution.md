## Table of Contents
1. [Examples in action](./examples_in_action.md)
2. [Overview of all rules](./all_rectors_overview.md)
3. [Installation](./installation.md)
4. [Configuration and Processing](./configuration_and_processing.md)
5. [Best practice guide](./best_practice_guide.md)
6. [Limitations](./limitations.md)
7. [Contribution](./contribution.md)

# Contributing

Want to help? Great!
Join the TYPO3 Slack channel [#ext-typo3-rector](https://typo3.slack.com/archives/C019R5LAA6A)

## Fork the project

Fork this project into your own account.

## Install TYPO3 Rector

You can use TYPO3 Rector with at least **PHP 7.4**. Your contributed code **MUST** also be compatible with PHP 7.4!

Install the project using composer:

```bash
git clone git@github.com:your-account/typo3-rector.git
cd typo3-rector
composer install
```

## Pick an issue from the list

https://github.com/sabbelasichon/typo3-rector/issues

**INFO** You can filter by tags where you can select, for example, only the easy ones by selecting "easy pick" or "good first issue".

## Assign the issue to yourself

Assign the issue to yourself if you are a member of the project, so others can see that you are working on it.
If you are not a member of the project, make a comment to let everyone know that you are working on it.

## Create an own Rector Rule

Run the following command and answer all questions:

```bash
bin/generate-rule
```

This command will ask you some questions to provide a proper rector setup.
Following this will lead to the creation of the overall rector structure necessary.
It will create the skeleton for the rector rule with the class, test class, fixtures and directories to start coding — basically everything you need to start!

### Useful infos:

- Use the PHP code parser to get the AST of it here: https://getrector.com/ast. This will help you to know on which node to listen to (for example `StaticCall`).
- the `refactor` must return a node, an array of nodes or null.
- keep it flat! Use early returns (with null) in case your conditions for migration are not met.
- the `getNodeTypes` method is used to define the use case of the function to migrate. It helps as well acting like an early return (see example below).
- helper functions and classes are provided via rector to make it easy for you to control further processing.
- here is a list of all php node types: https://github.com/rectorphp/php-parser-nodes-docs/blob/master/README.md
- search for similar rules and copy the code that is helpful for you. Frequent times you only need to make a few adjustments to existing rules.

### Example

In this example the methods `GeneralUtility::strtoupper(...)` and `GeneralUtility::strtolower(...)` are migrated.
- `getNodeTypes` checks for the StaticCall, preventing further rector execution if not met
- `refactor` first checks for the ObjectType to do an early return in case the class scanned is not `TYPO3\CMS\Core\Utility\GeneralUtility`
- after that, the actual function call is checked for being one of the functions to migrate

```php
final class GeneralUtilityToUpperAndLowerRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Utility\GeneralUtility')
        )) {
            return null;
        }

        if (! $this->isNames($node->name, ['strtoupper', 'strtolower'])) {
            return null;
        }

        // ...
    }
}
```

### Return multiple Nodes

Sometimes you need to return more than a single node.
A typical use case is if you want to create another method, for example.
In this case you need to listen to an `Expression`.
Do a full text search for `[Expression::class]` to find existing rules which can help you.

```php
final class MyRector extends AbstractRector implements DocumentedRuleInterface
{
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
        $methodCall = $node->expr;

        if (! $methodCall instanceof Node\Expr\MethodCall) {
            return null;
        }

        // More checks

        // Do your magic

        $methodCall = $this->nodeFactory->createMethodCall(...);

        return [new Expression($methodCall), $node];
    }
}
```

## Minimum PHP Version required

To run this rule only when a minimum PHP version is used, add this method with a required feature:

```php
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;

final class MyRector extends AbstractRector implements DocumentedRuleInterface, MinPhpVersionInterface
{
    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ENUM;
    }
}
```

## Check if class implements an interface

To check if a class implements an interface,
first the scope needs to be fetched and then checked by using class reflection.

```php
private function implementsInterface(ClassMethod|Class_ $node): bool
{
    $scope = ScopeFetcher::fetch($node);

    $classReflection = $scope->getClassReflection();
    if (! $classReflection instanceof ClassReflection) {
        return false;
    }

    return $classReflection->implementsInterface('Vendor\YourInterface');
}
```

## All Tests must be green

Make sure you have a test in place for your Rector

All unit tests must pass before submitting a pull request.
Additionally, the code style must be valid.
Run the following commands:

```bash
composer update
composer run-script local:contribute
vendor/bin/phpunit
```

Overall hints for testing:

- testing happens via fixture files (*.php.inc)
- those files display the code before and after execution, separated by `-----`
- rector keeps spaces etc. as its job is migration and not code cleaning, so keep that in mind. TCA fixture files don't need to look pretty!
- provide custom test classes via "Source" folder, that will be tested, but *will not* be affected by your rector to test and prevent side effects of your rule.
- Add stubs that reflect the original code

## Submit your changes

Great, now you can submit your changes in a pull request
