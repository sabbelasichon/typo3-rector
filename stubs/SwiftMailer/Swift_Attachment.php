<?php
if (class_exists('Swift_Attachment')) {
    return;
}

class Swift_Attachment
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
