<?php
declare(strict_types=1);

namespace TYPO3\CMS\Extbase\SignalSlot;

if(class_exists(Dispatcher::class)) {
    return;
}

final class Dispatcher
{
    public function connect($signalClassName, $signalName, $slotClassNameOrObject, $slotMethodName = '', $passSignalInformation = true): void
    {

    }

}
