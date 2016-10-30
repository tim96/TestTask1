<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Album;
use AppBundle\Entity\Image;
use AppBundle\Utils\TempFileCreator;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LoadImageData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    // run: php app/console doctrine:fixtures:load --append

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var TempFileCreator
     */
    private $tempFileCreator;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        try {
            $manager->getConnection()->beginTransaction();

            $kernelRootPath = $this->container->getParameter('kernel.root_dir');
            $filePath = $kernelRootPath.'/../web/bundles/framework/images/logo_symfony.png';
            $tempFilePath = $kernelRootPath.'/../web/bundles/framework/images/logo_symfony.png';

            $this->tempFileCreator = $this->container->get('app.temp_file_creator.service');

            $countAlbums = 5;
            for ($i = 1; $i <= $countAlbums; ++$i) {
                if ($i === 1) {
                    /** @var Album $album */
                    $album = $this->getReference('album'.$i);

                    $countImagesForFirstAlbum = 5;
                    for ($j = 1; $j <= $countImagesForFirstAlbum; ++$j) {
                        $image = $this->createImage($filePath, $tempFilePath);
                        $album->addImage($image);

                        $manager->persist($image);
                    }

                    $manager->persist($album);

                    continue;
                }

                /** @var Album $album */
                $album = $this->getReference('album'.$i);

                $countImagesForOtherAlbum = 21;
                for ($j = 1; $j <= $countImagesForOtherAlbum; ++$j) {
                    $image = $this->createImage($filePath, $tempFilePath);
                    $album->addImage($image);

                    $manager->persist($image);
                }

                $manager->persist($album);
            }

            $manager->flush();

            $manager->getConnection()->commit();
        } catch (\Exception $ex) {
            $manager->getConnection()->rollback();

            die('Error: '.$ex->getMessage().'; File: '.$ex->getFile().'; Line: '.$ex->getLine());
        }

        $manager->clear();
    }

    protected function createImage($filePath, $tempFilePath, $originalName = 'logo_symfony.png')
    {
        $tempPath = $this->tempFileCreator->createTempFile($filePath, $tempFilePath);

        $file = new UploadedFile($tempPath, $originalName, null, null, null, true);
        $image = new Image();
        $image->setFile($file);

        return $image;
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
