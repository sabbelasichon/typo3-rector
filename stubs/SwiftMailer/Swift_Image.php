<?php
if (class_exists('Swift_Image')) {
    return;
}

class Swift_Image
{
    /**
     * @param string $string
     * @return string
     */
    public static function fromPath($string)
    {
        $string = (string) $string;
        return 'foo';
    }
}
