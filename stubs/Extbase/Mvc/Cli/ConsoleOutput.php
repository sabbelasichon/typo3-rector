<?php
declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Mvc\Cli;

final class ConsoleOutput
{

    /**
     * Asks the user to select a value
     *
     * @param string|array $question The question to ask. If an array each array item is turned into one line of a multi-line question
     * @param array $choices List of choices to pick from
     * @param bool $default The default answer if the user enters nothing
     * @param bool $multiSelect If TRUE the result will be an array with the selected options. Multiple options can be given separated by commas
     * @param bool|int|null $attempts Max number of times to ask before giving up (null by default, which means infinite)
     * @return int|string|array The selected value or values (the key of the choices array)
     * @throws \InvalidArgumentException
     */
    public function select($question, $choices, $default = null, $multiSelect = false, $attempts = null)
    {

    }

    /**
     * Asks for a value and validates the response
     *
     * The validator receives the data to validate. It must return the
     * validated data when the data is valid and throw an exception
     * otherwise.
     *
     * @param string|array $question The question to ask. If an array each array item is turned into one line of a multi-line question
     * @param callable $validator A PHP callback that gets a value and is expected to return the (transformed) value or throw an exception if it wasn't valid
     * @param int|bool|null $attempts Max number of times to ask before giving up (null by default, which means infinite)
     * @param string $default The default answer if none is given by the user
     * @param array $autocomplete List of values to autocomplete. This only works if "stty" is installed
     * @return mixed
     * @throws \Exception When any of the validators return an error
     */
    public function askAndValidate($question, $validator, $attempts = null, $default = null, array $autocomplete = null)
    {

    }
}
