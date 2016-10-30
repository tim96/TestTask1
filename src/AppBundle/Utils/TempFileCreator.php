<?php

namespace AppBundle\Utils;

use Symfony\Component\Filesystem\Filesystem;

class TempFileCreator
{
    /**
     * @var Filesystem
     */
    protected $fileSystem;

    public function __construct(Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    public function createNewRandomFilePath($filePath)
    {
        return $filePath.mt_rand();
    }

    public function createTempFile($originalPath, $tempFilePath)
    {
        $tempFilePath = $this->createNewRandomFilePath($tempFilePath);
        $this->fileSystem->copy($originalPath, $tempFilePath);

        return $tempFilePath;
    }

    public function removeFile($path)
    {
        $this->fileSystem->remove($path);
    }
}
