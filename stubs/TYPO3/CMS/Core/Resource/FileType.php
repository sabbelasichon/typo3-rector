<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Resource;

if (enum_exists('TYPO3\CMS\Core\Resource\FileType')) {
    return;
}

enum FileType: int
{
    /**
     * any other file
     */
    case UNKNOWN = 0;

    /**
     * Any kind of text
     * @see http://www.iana.org/assignments/media-types/text
     */
    case TEXT = 1;

    /**
     * Any kind of image
     * @see http://www.iana.org/assignments/media-types/image
     */
    case IMAGE = 2;

    /**
     * Any kind of audio file
     * @see http://www.iana.org/assignments/media-types/audio
     */
    case AUDIO = 3;

    /**
     * Any kind of video
     * @see http://www.iana.org/assignments/media-types/video
     */
    case VIDEO = 4;

    /**
     * Any kind of application
     * @see http://www.iana.org/assignments/media-types/application
     */
    case APPLICATION = 5;
}
