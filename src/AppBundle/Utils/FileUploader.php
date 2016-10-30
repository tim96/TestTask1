<?php

namespace AppBundle\Utils;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $systemPath;

    private $targetDir;

    public function __construct($systemPath, $targetDir)
    {
        $this->systemPath = $systemPath;

        $this->targetDir = $targetDir;
    }

    protected function getServerDir()
    {
        return $this->systemPath.$this->targetDir;
    }

    public function getServerFilePath($fileName)
    {
        return $this->systemPath.$this->targetDir.DIRECTORY_SEPARATOR.$fileName;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }

    public function upload(UploadedFile $file)
    {
        mt_srand();

        // $randomString = substr(str_shuffle(md5(microtime())), 0, 10);
        $randomString = mt_rand();
        $hashFile = sha1($file->getClientOriginalName());
        $fileName = $hashFile.'.'.sha1($randomString).'.'.$file->guessExtension();

        $file->move($this->getServerDir(), $fileName);

        return $fileName;
    }

    public function getFileSize($path)
    {
        return filesize($path);
    }

    public function getFileSizeFormatter($path)
    {
        $size = $this->getFileSize($path);
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;

        return number_format($size / pow(1024, $power), 2, '.', ',').' '.$units[$power];
    }
}
