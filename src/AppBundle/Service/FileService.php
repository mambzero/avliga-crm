<?php


namespace AppBundle\Service;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{

    const UPLOADS_DIR = 'uploads';

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var string
     */
    private $mainDir;

    /**
     * @var string
     */
    private $subDir;

    /**
     * @var UploadedFile
     */

    private $file;

    /**
     * @var string
     */

    private $fileUrl;

    /**
     * FileHandler constructor.
     * @param string $rootDir
     */
    public function __construct($rootDir)
    {

        $this->rootDir = $rootDir.'/web';
        $this->mainDir = '/'.self::UPLOADS_DIR;

        $uploads = $this->rootDir.$this->mainDir;

        if (file_exists($uploads)==false) {
            mkdir($uploads);
        }

    }

    /**
     * @param string $subDir
     * @return FileService
     */

    public function setSubDir($subDir) {

        $this->subDir = '/'.$subDir;
        $subDir = $this->rootDir.$this->mainDir.$this->subDir;

        if (!file_exists($subDir)) {
            mkdir($subDir);
        }

        $this->mainDir = $this->mainDir.$this->subDir;

        return $this;

    }

    /**
     * @param UploadedFile | string $file
     * @return FileService
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @param string $fileUrl
     */
    private function setFileUrl($fileUrl)
    {
        $this->fileUrl = $fileUrl;
    }

    /**
     * @return UploadedFile
     */
    private function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getFileUrl()
    {
        return $this->fileUrl;
    }

    /**
     * @return FileService|string
     */

    public function uploadFile()
    {
        $file = $this->getFile();
        $fileName = $file->getClientOriginalName();
        $path = $this->rootDir.$this->mainDir;

        try {
            $file->move($path,$fileName);
        } catch (FileException $e) {
            return $e->getMessage();
        }

        $url = $this->mainDir.'/'.$fileName;

        $this->setFileUrl($url);

        return $this;
    }

    /**
     * @param string
     * @return FileService
     */

    public function deleteFile($fileUrl) {
        $filePath = $this->rootDir.$fileUrl;
        if (file_exists($filePath) && isset($fileUrl)) {
            unlink($filePath);
        }

        return $this;
    }
}