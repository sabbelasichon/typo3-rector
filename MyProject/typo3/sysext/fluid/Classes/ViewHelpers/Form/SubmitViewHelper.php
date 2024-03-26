<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Fluid\ViewHelpers\Form;

/**
 * Creates a submit button.
 *
 * Examples
 * ========
 *
 * Defaults
 * --------
 *
 * ::
 *
 *    <f:form.submit value="Send Mail" />
 *
 * Output::
 *
 *    <input type="submit" />
 *
 * Dummy content for template preview
 * ----------------------------------
 *
 * ::
 *
 *    <f:form.submit name="mySubmit" value="Send Mail"><button>dummy button</button></f:form.submit>
 *
 * Output::
 *
 *    <input type="submit" name="mySubmit" value="Send Mail" />
 */
final class SubmitViewHelper extends AbstractFormFieldViewHelper
{
    /**
     * @var string
     */
    protected $tagName = 'input';

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerTagAttribute(
            'disabled',
            'string',
            'Specifies that the input element should be disabled when the page loads'
        );
        $this->registerUniversalTagAttributes();
    }

    public function render(): string
    {
        $name = $this->getName();
        $this->registerFieldNameForFormTokenGeneration($name);

        $this->tag->addAttribute('type', 'submit');
        $this->tag->addAttribute('value', $this->getValueAttribute());
        if (!empty($name)) {
            $this->tag->addAttribute('name', $name);
        }

        return $this->tag->render();
    }
}
