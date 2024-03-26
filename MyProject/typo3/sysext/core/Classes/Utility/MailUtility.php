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

namespace TYPO3\CMS\Core\Utility;

use TYPO3\CMS\Core\Mail\Rfc822AddressesParser;

/**
 * Class to handle mail specific functionality
 */
class MailUtility
{
    /**
     * Gets a valid "from" for mail messages (email and name).
     *
     * Ready to be passed to $mail->setFrom()
     *
     * This method can return three different variants:
     * 1. An assoc. array: key => Valid email address which can be used as sender; value => Valid name which can be used as a sender
     * 2. A numeric array with one entry: Valid email address which can be used as sender
     * 3. Null, if no address is configured
     *
     * @return array<string|int, string>|null
     */
    public static function getSystemFrom(): ?array
    {
        $address = self::getSystemFromAddress();
        $name = self::getSystemFromName();
        if (!$address) {
            return null;
        }
        if ($name) {
            return [$address => $name];
        }
        return [$address];
    }

    /**
     * Creates a valid "from" name for mail messages.
     *
     * As configured in Install Tool.
     *
     * @return string|null The name (unquoted, unformatted). NULL if none is set or an invalid non-string value.
     */
    public static function getSystemFromName(): ?string
    {
        $name = $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromName'] ?? null;

        return (!empty($name) && is_string($name)) ? $name : null;
    }

    /**
     * Creates a valid email address for the sender of mail messages.
     *
     * Uses a fallback chain:
     * $TYPO3_CONF_VARS['MAIL']['defaultMailFromAddress'] ->
     * no-reply@FirstDomainRecordFound ->
     * no-reply@php_uname('n') ->
     * no-reply@example.com
     *
     * Ready to be passed to $mail->setFrom()
     *
     * @return string An email address
     */
    public static function getSystemFromAddress(): string
    {
        $address = $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'] ?? null;

        if (!is_string($address) || !GeneralUtility::validEmail($address)) {
            // still nothing, get host name from server
            $address = 'no-reply@' . php_uname('n');
            if (!GeneralUtility::validEmail($address)) {
                // if everything fails use a dummy address
                $address = 'no-reply@example.com';
            }
        }
        return $address;
    }

    /**
     * Gets a default "reply-to" for mail messages (email and name).
     *
     * Ready to be passed to $mail->setReplyTo()
     *
     * This method returns a list of email addresses, but depending on the existence of "defaultMailReplyToName"
     * the array can have a different shape:
     *
     * 1. An assoc. array: key => a valid reply-to address which can be used as sender; value => a valid reply-to name which can be used as a sender
     * 2. A numeric array with one entry: a valid reply-to address which can be used as sender
     *
     * @return array<string|int, string>
     */
    public static function getSystemReplyTo(): array
    {
        $mailConfiguration = $GLOBALS['TYPO3_CONF_VARS']['MAIL'] ?? [];
        $replyToAddress = $mailConfiguration['defaultMailReplyToAddress'] ?? null;
        if (empty($replyToAddress) || !GeneralUtility::validEmail($replyToAddress)) {
            return [];
        }

        if (!empty($mailConfiguration['defaultMailReplyToName'])) {
            $replyTo = [$replyToAddress => $mailConfiguration['defaultMailReplyToName']];
        } else {
            $replyTo = [$replyToAddress];
        }

        return $replyTo;
    }

    /**
     * Breaks up a single line of text for emails
     * Words - longer than $lineWidth - will not be split into parts
     *
     * @param string $str The string to break up
     * @param string $newlineChar The string to implode the broken lines with (default/typically \n)
     * @param int $lineWidth The line width
     * @return string Reformatted text
     */
    public static function breakLinesForEmail(string $str, string $newlineChar = LF, int $lineWidth = 76): string
    {
        $lines = [];
        $substrStart = 0;
        while (strlen($str) > $substrStart) {
            $substr = substr($str, $substrStart, $lineWidth);
            // has line exceeded (reached) the maximum width?
            if (strlen($substr) === $lineWidth) {
                // find last space-char
                $spacePos = strrpos(rtrim($substr), ' ');
                // space-char found?
                if ($spacePos !== false) {
                    // take everything up to last space-char
                    $theLine = substr($substr, 0, $spacePos);
                    $substrStart++;
                } else {
                    // search for space-char in remaining text
                    // makes this line longer than $lineWidth!
                    $afterParts = explode(' ', substr($str, $lineWidth + $substrStart), 2);
                    $theLine = $substr . $afterParts[0];
                }
                if ($theLine === '') {
                    // prevent endless loop because of empty line
                    break;
                }
            } else {
                $theLine = $substr;
            }
            $lines[] = trim($theLine);
            $substrStart += strlen($theLine);
            if (trim(substr($str, $substrStart, $lineWidth)) === '') {
                // no more text
                break;
            }
        }
        return implode($newlineChar, $lines);
    }

    /**
     * Parses mailbox headers and turns them into an array.
     *
     * Mailbox headers are a comma separated list of 'name <email@example.org>' combinations
     * or plain email addresses (or a mix of these).
     * The resulting array has key-value pairs where the key is either a number
     * (no display name in the mailbox header) and the value is the email address,
     * or the key is the email address and the value is the display name.
     *
     * @param string $rawAddresses Comma separated list of email addresses (optionally with display name)
     * @return array Parsed list of addresses.
     */
    public static function parseAddresses(string $rawAddresses): array
    {
        $addressParser = GeneralUtility::makeInstance(
            Rfc822AddressesParser::class,
            $rawAddresses
        );
        $addresses = $addressParser->parseAddressList();
        $addressList = [];
        foreach ($addresses as $address) {
            if ($address->mailbox === '') {
                continue;
            }
            if ($address->personal) {
                // item with name found ( name <email@example.org> )
                $addressList[$address->mailbox . '@' . $address->host] = $address->personal;
            } else {
                // item without name found ( email@example.org )
                $addressList[] = $address->mailbox . '@' . $address->host;
            }
        }
        return $addressList;
    }
}
