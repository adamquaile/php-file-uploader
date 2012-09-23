<?php

/**
 * All validators must adhere to this interface.
 */
interface FileUploadValidator {
    
    /**
     * Inform the validator about which file we're dealing with
     *
     * @abstract
     * @param UploadedFile $file
     * @return void
     */
    public function setUploadedFile(UploadedFile $file);

    /**
     * Get an array of errors (empty array indicates validation success).
     *
     * @abstract
     * @return array
     */
    public function getErrors();
}