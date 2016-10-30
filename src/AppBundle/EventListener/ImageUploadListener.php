<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Image;
use AppBundle\Utils\FileUploader;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class ImageUploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new \DateTime());
        }

        $this->uploadFile($entity);
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $fileName = $entity->getFile();

        $entity->setFile(new File($this->uploader->getServerFilePath($fileName)));
    }

    private function uploadFile($entity)
    {
        if (!$entity instanceof Image) {
            return;
        }

        $file = $entity->getFile();

        if (!$file instanceof UploadedFile) {
            return;
        }

        if (null !== $entity->getFilePath()) {
            // remove previous file if nessesary
        }

        $fileName = $this->uploader->upload($file);
        $entity->setFilePath($fileName);

        $filePath = $this->uploader->getServerFilePath($fileName);
        $entity->setFileSize($this->uploader->getFileSize($filePath));
        $entity->setFileSizeFormatted($this->uploader->getFileSizeFormatter($filePath));
    }
}
