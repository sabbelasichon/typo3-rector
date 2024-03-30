## Table of Contents
1. [Examples in action](./examples_in_action.md)
2. [Overview of all rules](./all_rectors_overview.md)
3. [Installation](./installation.md)
4. [Configuration and Processing](./configuration_and_processing.md)
5. [Best practice guide](./best_practice_guide.md)
6. [Limitations](./limitations.md)
7. [Contribution](./contribution.md)

# Examples in action
Let's say you have a Fluid ViewHelper looking like this:

```php
class InArrayViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Checks if given $uid is in a given $array
     *
     * @param int $uid the uid
     * @param array $arrayToCheckAgainst the array to check for the given $uid
     * @return bool
     */
    public function render($uid, array $arrayToCheckAgainst)
    {
        if (in_array($uid, $arrayToCheckAgainst)) {
           return true;
        } else {
           return false;
        }
    }
}
```

What's "wrong" with this code? Well, it depends on the context, but if we assume you would like to have this code ready
for TYPO3 version 10 you should move the render method arguments to the method `initializeArguments` and you should
rename the namespace `\TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper` to
`\TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper`.

And we are not talking about the superfluous else statement or not having Type Declarations.
That's a different story.

Do you like to do these changes manually on a codebase with let's say 40-100 ViewHelpers? We don't.
So let Rector do the heavy work for us and apply the "rules" `MoveRenderArgumentsToInitializeArgumentsMethodRector` and
`RenameClassMapAliasRector` for Version 9.5.

Rector transforms this code for us to the following one:

```php
class InArrayViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('uid', 'int', 'the uid', true);
        $this->registerArgument('arrayToCheckAgainst', 'array', 'the array to check for the given $uid', true);
    }

    /**
     * Checks if given $uid is in a given $array
     *
     * @return bool
     */
    public function render()
    {
        $uid = $this->arguments['uid'];
        $arrayToCheckAgainst = $this->arguments['arrayToCheckAgainst'];
        if (in_array($uid, $arrayToCheckAgainst)) {
           return true;
        } else {
           return false;
        }
    }
}
```
Isn't this amazing?

Let's see another one:

```php
final class SomeService
{
    /**
     * @var \Ssch\TYPO3Rector\Tests\Rector\Annotation\Source\InjectionClass
     * @inject
     */
    protected $injectMe;
}
```

TYPO3 switched to Doctrine Annotations and you should better use either constructor injection or setter injection.
Again, if you have only one class, this change is not a problem.
But most of the time you have hundreds of them, and you have to remember what to do.
This is cumbersome and error-prone. So let's run Rector for us with the `InjectAnnotationRector` and you get this:

```php
use Ssch\TYPO3Rector\Tests\Rector\Annotation\Source\InjectionClass;

final class SomeInjectClass
{
    /**
     * @var \Ssch\TYPO3Rector\Tests\Rector\Annotation\Source\InjectionClass
     */
    protected $injectMe;

    public function injectInjectMe(InjectionClass $inject): void
    {
        $this->inject = $inject;
    }
}
```

Cool. Let me show you one more example.

Let's say you want to upgrade from version 9 to 10, and you have the following code:

```php
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Configuration\Exception\NoSuchOptionException;

class MyActionController extends ActionController
{
    public function exceptionAction()
    {
        $foo = 'foo';
        $bar = 'bar';
        if ($foo !== $bar) {
            throw new NoSuchOptionException();
        }
    }
}
```

Can you spot the error? Guess not. At least I couldn't.
The exception class `NoSuchOptionException` does not exist anymore in version 10.
What? But it still worked in version 9. Why?
Because TYPO3 offers a nice way to deprecate such changes for one major version with these handy `ClassAliasMap.php` files.
But, postponed is not abandoned. You have to react to these changes at a certain time.
Do you know all these changes by heart? Sure not.

So, again, let rector do it for you with the `RenameClassMapAliasRector`.

And there is more...

...**look at the overview of [all available TYPO3 Rectors](/docs/all_rectors_overview.md)** with before/after diffs and configuration examples.

