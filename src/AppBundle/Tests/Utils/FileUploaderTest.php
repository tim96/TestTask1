<?php

namespace AppBundle\Tests\Utils;

use AppBundle\Utils\FileUploader;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FileUploader
     */
    protected $fileUploaderService;

    /**
     * @var string
     */
    protected $systemPath;

    /**
     * @var string
     */
    protected $targetDir;

    public function setUp()
    {
        $this->systemPath = __DIR__.'/../Data';
        $this->targetDir = '/uploads';

        $this->fileUploaderService = new FileUploader($this->systemPath, $this->targetDir);
    }

    public function testUpload()
    {
        $filename = 'logo_symfony.png';
        $tempFilename = 'logo_symfony_temp.png';
        $path = $this->systemPath.DIRECTORY_SEPARATOR.$filename;
        $temp = $this->systemPath.DIRECTORY_SEPARATOR.$tempFilename;

        $filesystem = new Filesystem();
        $filesystem->copy($path, $temp);

        $file = new UploadedFile($temp, $filename, null, null, null, true);

        $newFileName = $this->fileUploaderService->upload($file);
        $this->assertTrue(is_file($this->fileUploaderService->getServerFilePath($newFileName)));

        $filesystem->remove($this->fileUploaderService->getServerFilePath($newFileName));
        $filesystem->remove($temp);
    }
}
