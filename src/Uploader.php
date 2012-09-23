<?php

/**
 * Class to handle the upload of files through an HTML form.
 *
 * Currently handles single file uploads based on input's name
 * attribute.
 *
 * Validation is done by adding any number of validators,
 * e.g. FileSizeValidator, IsImageValidator.
 *
 * Example usage in sample/ directory
 */
class Uploader {

    private $uploadDirectory;
    private $uploadKey;

    private $validators = array();

    public function __construct($uploadDirectory, $uploadKey) {

        $this->uploadDirectory = $uploadDirectory;
        $this->uploadKey = $uploadKey;

        // Some sanity checks here. Throwing as early as possible
        if (!is_dir($uploadDirectory)) {
            throw new RuntimeException("Given upload location is not a directory. [" . $uploadDirectory.']');
        }
        if (!is_writable($uploadDirectory)) {
            throw new RuntimeException("Directory " . $uploadDirectory.' is not writeable. Check permissions');
        }

    }

    /**
     * Add a validator
     * @param FileUploadValidator $validator
     */
    public function addValidator(FileUploadValidator $validator) {
        $this->validators[] = $validator;
    }

    /**
     * Get the file which was uploaded.
     *
     * Advisable to check isValid first, as exceptions might otherwise be thrown.
     *
     * @return UploadedFile
     */
    public function getFile() {
        return UploadedFile::createFromGlobal($this->uploadKey);
    }

    /**
     * Check if the file passes all required validation rules. Rules
     * can be added with addValidator.
     *
     * Get the list of errors by reference
     *
     * @param array $returnErrors Errors (or empty array) will be filled into this array
     * @return bool
     */
    public function isValid(&$returnErrors=array()) {

        try {

            $file = UploadedFile::createFromGlobal($this->uploadKey);
        } catch (UploadNativeException $e) {

            $returnErrors[] = $e->getMessage();
            return false;
        }

        $validationErrors = array();

        foreach ($this->validators as $validator) {
            /**
             * @var FileUploadValidator $validator
             */
            $validator->setUploadedFile($file);
            $validationErrors += $validator->getErrors();

        }

        $returnErrors = $validationErrors;
        return count($validationErrors) === 0;

    }

    /**
     * Do the upload, optionally renaming the file.
     *
     * You should check isValid before calling this.
     *
     * Renaming the file can be useful in case two users upload
     * a file with the same name.
     *
     * @param null $renameFile              How to rename the file. No need to specify
     *                                      extension if $renameButKeepExtension is true
     * @param bool $renameButKeepExtension  Whether to keep the extension the file was originally
     *                                      uploaded with.
     * @return string                       The name of the uploaded file (not including path)
     *
     * @throws RuntimeException
     */
    public function moveFileToDestination($renameFile=null, $renameButKeepExtension=false) {

        // Get file from $_FILES
        $file = UploadedFile::createFromGlobal($this->uploadKey);

        $srcFile = $file->getPath();

        // Decide on filename to move to
        if (is_null($renameFile)) {

            $targetName = $file->getName();
        } else {

            $targetName = $renameFile;

            if ($renameButKeepExtension) {
                $parts = explode('.', $file->getName());
                if (count($parts) > 1) {
                    $ext = $parts[count($parts) - 1];

                    $targetName = $renameFile.'.'.$ext;
                }
            }
        }
        $targetFile = $this->uploadDirectory . DIRECTORY_SEPARATOR . $targetName;

        // Do the move
        $success = move_uploaded_file($srcFile, $targetFile);
        if ($success) {

            return $targetName;
        } else {

            throw new RuntimeException("File could not be uploaded. ");
        }
    }

}
