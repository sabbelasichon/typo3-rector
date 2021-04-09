<?php
declare(strict_types=1);

if (class_exists(Swift_Image::class)) {
    return;
}

class Swift_Image
{
    public static function fromPath(string $string): string
    {
        return 'foo';
    }
}
