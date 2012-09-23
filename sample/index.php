<?php

require_once '../src/autoload.php';

try {
    $uploader = new Uploader('./uploads', 'upload');
    $uploader->addValidator(new FileSizeValidator(1024*1024*2));


    if (!empty($_POST)) {
        if ($uploader->isValid($errors)) {
            $uploader->moveFileToDestination('foo', true);
        } else {
            var_dump($errors);
        }
    }

} catch (Exception $e) {
    echo $e->getMessage();exit;
}

require 'upload-form.tpl.php';
