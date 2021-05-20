<?php
declare(strict_types=1);

namespace TYPO3\CMS\Extbase\SignalSlot;

if(class_exists('TYPO3\CMS\Extbase\SignalSlot\Dispatcher')) {
    return;
}

class Dispatcher
{
    public function connect($signalClassName, $signalName, $slotClassNameOrObject, $slotMethodName = '', $passSignalInformation = true): void
    {

    }

}
