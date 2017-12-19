<?php
/**
 * This file is part of me-tools.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mirko Pagliai
 * @link        https://github.com/mirko-pagliai/me-tools
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */
namespace MeTools\Controller\Component;

use Cake\Controller\Component;
use Cake\Filesystem\Folder;
use Cake\Network\Exception\InternalErrorException;

/**
 * A component to upload files
 */
class UploaderComponent extends Component
{
    /**
     * Last error
     * @var string
     */
    protected $error;

    /**
     * Uploaded file information
     * @var object
     */
    protected $file;

    /**
     * Internal method to set an error
     * @param string $error Error
     * @return void
     * @uses $error
     */
    protected function setError($error)
    {
        if (!$this->error) {
            $this->error = $error;
        }
    }

    /**
     * Internal method to find the target filename
     * @param string $target Path
     * @return string
     */
    protected function findTargetFilename($target)
    {
        //If the file already exists, adds a numeric suffix
        if (file_exists($target)) {
            $dirname = dirname($target) . DS;
            $filename = pathinfo($target, PATHINFO_FILENAME);
            $extension = pathinfo($target, PATHINFO_EXTENSION);

            //Initial tmp name
            $tmp = $dirname . $filename;

            for ($i = 1;; $i++) {
                $target = $tmp . '_' . $i;

                if ($extension) {
                    $target .= '.' . $extension;
                }

                if (!file_exists($target)) {
                    break;
                }
            }
        }

        return $target;
    }

    /**
     * This allows you to override the `move_uploaded_file()` function, for
     *  example with the `rename()` function
     * @param string $filename The filename of the uploaded file
     * @param string $destination The destination of the moved file
     * @return bool
     */
    //@codingStandardsIgnoreLine
    protected function move_uploaded_file($filename, $destination)
    {
        return move_uploaded_file($filename, $destination);
    }

    /**
     * Returns the first error
     * @return string|bool String or `false`
     * @uses $error
     */
    public function error()
    {
        return $this->error ?: false;
    }

    /**
     * Checks if the mimetype is correct
     * @param string|array $acceptedMimetype Accepted mimetypes as string or
     *  array or a magic word (`images` or `text`)
     * @return \MeTools\Controller\Component\UploaderComponent
     * @throws InternalErrorException
     * @uses setError()
     * @uses $file
     */
    public function mimetype($acceptedMimetype)
    {
        if (empty($this->file)) {
            throw new InternalErrorException(__d('me_tools', 'There are no uploaded file information'));
        }

        //Changes magic words
        switch ($acceptedMimetype) {
            case 'image':
                $acceptedMimetype = ['image/gif', 'image/jpeg', 'image/png'];
                break;
            case 'text':
                $acceptedMimetype = ['text/plain'];
                break;
        }

        $currentMimetype = mime_content_type($this->file->tmp_name);

        if (!in_array($currentMimetype, (array)$acceptedMimetype)) {
            $this->setError(__d('me_tools', 'The mimetype {0} is not accepted', $currentMimetype));
        }

        return $this;
    }

    /**
     * Saves the file
     * @param string $directory Directory where you want to save the uploaded
     *  file
     * @return string|bool Final full path of the uploaded file or `false` on
     *  failure
     * @uses findTargetFilename()
     * @uses setError()
     * @uses error()
     * @uses move_uploaded_file()
     * @uses $file
     */
    public function save($directory)
    {
        if (!$this->file) {
            throw new InternalErrorException(__d('me_tools', 'There are no uploaded file information'));
        }

        //Checks for previous errors
        if ($this->error()) {
            return false;
        }

        if (!is_dir($directory)) {
            throw new InternalErrorException(__d('me_tools', 'Invalid or no existing directory {0}', $directory));
        }

        //Adds slash term
        if (!Folder::isSlashTerm($directory)) {
            $directory .= DS;
        }

        //Gets the target full path
        $file = $this->findTargetFilename($directory . $this->file->name);

        if (!$this->move_uploaded_file($this->file->tmp_name, $file)) {
            $this->setError(__d('me_tools', 'The file was not successfully moved to the target directory'));

            return false;
        }

        return $file;
    }

    /**
     * Sets uploaded file information (`$_FILES` array, better as
     *  `$this->request->getData('file')`)
     * @param array $file Uploaded file information
     * @return \MeTools\Controller\Component\UploaderComponent
     * @uses setError()
     * @uses $error
     * @uses $file
     */
    public function set($file)
    {
        //Resets `$error`
        unset($this->error);

        $this->file = (object)$file;

        //Errors messages
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the maximum size that was specified in php.ini',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the maximum size that was specified in the HTML form',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension',
            'default' => 'Unknown upload error',
        ];

        //Checks errors during upload
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            //Gets the default error message, if the error can not be
            //  identified or if the key is not present
            if (!isset($file['error']) || !array_key_exists($file['error'], $errors)) {
                $file['error'] = 'default';
            }

            $this->setError($errors[$file['error']]);

            return $this;
        }

        return $this;
    }
}
