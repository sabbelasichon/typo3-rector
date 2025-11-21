<?php

namespace Symfony\Component\Mime;

if (class_exists('Symfony\Component\Mime\Message')) {
    return;
}

class Message extends RawMessage
{
}
