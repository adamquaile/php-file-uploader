<?php

/**
 * Validate an UploadedFile based on file size.
 */
class FileSizeValidator implements FileUploadValidator {

    /**
     * @var UploadedFile $file
     */
    private $file;

    private $maxBytes;
    private $minBytes;

    public function __construct($maxBytes = null, $minBytes = null) {
        $this->maxBytes = $maxBytes;
        $this->minBytes = $minBytes;
    }

    public function setUploadedFile(UploadedFile $file)
    {
        $this->file = $file;
    }

    public function getErrors()
    {
        $errors = array();

        if (!is_null($this->maxBytes) && $this->file->getSize() > $this->maxBytes) {

            $errors[] = 'File Too Large';

        } elseif (!is_null($this->minBytes) && $this->file->getSize() < $this->minBytes) {

            $errors[] = 'File Too Small';

        }

        return $errors;
    }

}