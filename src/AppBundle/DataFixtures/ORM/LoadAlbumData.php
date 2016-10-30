<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Album;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadAlbumData extends AbstractFixture implements OrderedFixtureInterface
{
    // run: php app/console doctrine:fixtures:load --append

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        try {
            $manager->getConnection()->beginTransaction();

            $albums = array(
                array('name' => 'One'),
                array('name' => 'Two'),
                array('name' => 'Three'),
                array('name' => 'Four'),
                array('name' => 'Five'),
            );

            foreach ($albums as $key => $album) {
                $tempAlbum = new Album();
                $tempAlbum->setAlbumName($album['name']);

                $manager->persist($tempAlbum);

                $this->addReference('album'.($key + 1), $tempAlbum);
            }

            $manager->flush();

            $manager->getConnection()->commit();
        } catch (\Exception $ex) {
            $manager->getConnection()->rollback();

            die('Error: '.$ex->getMessage().'; File: '.$ex->getFile().'; Line: '.$ex->getLine());
        }

        $manager->clear();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
