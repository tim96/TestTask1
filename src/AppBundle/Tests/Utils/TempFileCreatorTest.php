<?php

namespace AppBundle\Tests\Utils;

use AppBundle\Utils\TempFileCreator;
use Symfony\Component\Filesystem\Filesystem;

class TempFileCreatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TempFileCreator
     */
    protected $tempFileCreatorService;

    public function setUp()
    {
        $filesystem = new Filesystem();
        $this->tempFileCreatorService = new TempFileCreator($filesystem);
    }

    public function testCreateNewRandomFilePath()
    {
        $tempPath = __DIR__.'/../Data/logo_symfony_temp.png';

        $newTempPath = $this->tempFileCreatorService->createNewRandomFilePath($tempPath);

        $this->assertNotEquals($tempPath, $newTempPath);
    }

    public function testCreateTempFile()
    {
        $originalPath = __DIR__.'/../Data/logo_symfony.png';
        $tempPath = __DIR__.'/../Data/logo_symfony_temp.png';

        $newTempPath = $this->tempFileCreatorService->createTempFile($originalPath, $tempPath);
        $this->assertTrue(is_file($newTempPath));

        $this->tempFileCreatorService->removeFile($newTempPath);
        $this->assertFalse(is_file($newTempPath));
    }
}
