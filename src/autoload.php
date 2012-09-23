<?php

/**
 * While this lib is very simple, all classes are stored
 * in one flat directory.
 *
 * For compatibility with older versions of PHP, namespaces
 * aren't used.
 *
 * @param $class The Class name
 */
function uploader_autoload($class) {

    $f = dirname(__FILE__).'/'.$class.'.php';

    if (file_exists($f)) {
        require $f;
    }
}
spl_autoload_register('uploader_autoload');