<?php

namespace Ssch\TYPO3Rector\Tests\v10\v1\SendNotifyEmailToMailApi\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;

class TxSomePluginBase extends AbstractPlugin
{
    /**
     * @var object|ContentObjectRenderer
     */
    private $local_cObj;

    /**
     * @var array
     */
    private $formData = [];

    /**
     * @var array
     */
    private $conf = [];

    function main($content, $conf)
    {
        $this->local_cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $this->formData = [];
    }

    function sendEmailWithEveryOption()
    {
        return $this->local_cObj->sendNotifyEmail(implode("\n",[]), $this->formData['tx_interacallforpapers']['DATA']['email'], '',$this->conf["fromAddr"],$this->conf["fromName"], $this->conf["replyTo"]);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\v10\v1\SendNotifyEmailToMailApi\Fixture;

use TYPO3\CMS\Core\Mail\MailMessage;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Utility\MailUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;

class TxSomePluginBase extends AbstractPlugin
{
    /**
     * @var object|ContentObjectRenderer
     */
    private $local_cObj;

    /**
     * @var array
     */
    private $formData = [];

    /**
     * @var array
     */
    private $conf = [];

    function main($content, $conf)
    {
        $this->local_cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $this->formData = [];
    }

    function sendEmailWithEveryOption()
    {
        $success = false;
        $mail = GeneralUtility::makeInstance(MailMessage::class);
        $message = trim(implode("\n",[]));
        $senderName = trim($this->conf["fromName"]);
        $senderAddress = trim($this->conf["fromAddr"]);
        if ($senderAddress !== '') {
            $mail->from(new Address($senderAddress, $senderName));
        }
        $parsedReplyTo = MailUtility::parseAddresses($this->conf["replyTo"]);
        if (!empty($parsedReplyTo)) {
            $mail->setReplyTo($parsedReplyTo);
        }
        if ($message !== '') {
            $messageParts = explode(LF, $message, 2);
            $subject = trim($messageParts[0]);
            $plainMessage = trim($messageParts[1]);
            $parsedRecipients = MailUtility::parseAddresses($this->formData['tx_interacallforpapers']['DATA']['email']);
            if (!empty($parsedRecipients)) {
                $mail->to(...$parsedRecipients)->subject($subject)->text($plainMessage);
                $mail->send();
            }
            $success = true;
        }
        return $success;
    }
}

?>
