<?php

namespace Symfony\Component\Mime;

if (class_exists('Symfony\Component\Mime\Email')) {
    return;
}

class Email extends Message
{
}
