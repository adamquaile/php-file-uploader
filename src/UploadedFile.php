<?php

/**
 * Class to represent an uploaded file.
 */
class UploadedFile {

    private $name;
    private $path;
    private $size;

    public function __construct($name, $path) {
        $this->name = $name;
        $this->path = $path;
        $this->size = filesize($this->path);
    }

    public function getSize() {
        return $this->size;
    }
    public function getName()
    {
        return $this->name;
    }

    public function getPath()
    {
        return $this->path;
    }


    /**
     * Convert a $_FILES array into an object.
     *
     * @static
     * @param $uploadKey
     * @return UploadedFile
     * @throws UploadNativeException
     */
    public static function createFromGlobal($uploadKey) {
        $uploadData = $_FILES[$uploadKey];

        if ($uploadData['error'] != UPLOAD_ERR_OK) {
            throw UploadNativeException::fromErrorCode($uploadData['error']);
        }

        return new UploadedFile($uploadData['name'], $uploadData['tmp_name']);
    }


}