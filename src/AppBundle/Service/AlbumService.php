<?php

namespace AppBundle\Service;

use AppBundle\Entity\Album;
use AppBundle\Entity\Image;
use AppBundle\Repository\AlbumRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AlbumService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var AlbumRepository
     */
    protected $repository;

    /**
     * AlbumService constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct($entityManager)
    {
        $this->em = $entityManager;
        $this->repository = $entityManager->getRepository('AppBundle:Album');
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function getListQueryBuilder()
    {
        return $this->repository->getListQueryBuilder();
    }

    public function getListWithImageMinQueryBuilder($maxCountImages = 10)
    {
        return $this->repository->getListWithImageMinQueryBuilder($maxCountImages);
    }

    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    public function getRecordById($id)
    {
        $record = $this->getById($id);
        if (count($record) < 1) {
            throw new NotFoundHttpException();
        }

        return $record[0];
    }

    /**
     * @param Album $album
     * @param array $originalImages
     *
     * @return Album
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function save($album, $originalImages = array())
    {
        foreach ($originalImages as $image) {
            if (false === $album->getImages()->contains($image)) {
                $album->removeImage($image);
                $this->em->remove($image);
            }
        }

/** @var Image $image */
        // it is just a trick to fire up doctrine event
        // to upload new files if it exists
        foreach ($album->getImages() as $image) {
            $image->setUpdatedAt(new \DateTime());
        }

        $this->em->persist($album);
        $this->em->flush();

        return $album;
    }
}
