<?php

/**
 * Quickly create an exception based on one of the standard PHP
 * UPLOAD_ERR_* constants.
 */
class UploadNativeException extends RuntimeException
{
    public static function fromErrorCode($errorCode) {

        $upload_errors = array(
            UPLOAD_ERR_OK           => "No errors.",
            UPLOAD_ERR_INI_SIZE     => "Larger than upload_max_filesize.",
            UPLOAD_ERR_FORM_SIZE    => "Larger than form MAX_FILE_SIZE.",
            UPLOAD_ERR_PARTIAL      => "Partial upload.",
            UPLOAD_ERR_NO_FILE      => "No file.",
            UPLOAD_ERR_NO_TMP_DIR   => "No temporary directory.",
            UPLOAD_ERR_CANT_WRITE   => "Can't write to disk.",
            UPLOAD_ERR_EXTENSION    => "File upload stopped by extension.",
            UPLOAD_ERR_EMPTY        => "File is empty."
        );

        return new UploadNativeException($upload_errors[$errorCode]);
    }
}