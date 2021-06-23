<?php

// Autoload all my stubs
spl_autoload_register(function(string $className) {
    $path = str_replace('\\', '/', $className) . '.stub';
    $filePath = __DIR__ . '/../stubs/' .$path;
    if(file_exists($filePath)) {
        require $filePath;
    }
});
