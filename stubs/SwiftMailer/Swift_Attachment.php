<?php
declare(strict_types=1);

if (class_exists('Swift_Attachment')) {
    return;
}

class Swift_Attachment
{
    public static function fromPath(string $string): string
    {
        return 'foo';
    }
}
