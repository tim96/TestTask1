<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image.
 *
 * @ORM\Table(name="project_image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Record created time.
     *
     * @var \DateTime
     *
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     *
     * @ORM\Column(name="created_at", type="datetime")
     **/
    private $createdAt;

    /**
     * Record updated time.
     *
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     **/
    private $updatedAt;

    /**
     * Path for the image file.
     *
     * @var string
     *
     * @ORM\Column(name="file_path", type="text", length=255, nullable=false)
     */
    protected $filePath;

    /**
     * Image file.
     *
     * @var File
     *
     * @Assert\File(
     *     mimeTypes={ "image/gif", "image/jpeg", "image/png", "image/tiff", "image/svg+xml" }
     * )
     */
    protected $file;

    /**
     * Image file size formatted.
     *
     * @var string
     *
     * @ORM\Column(name="file_size_formatted", type="string", length=255, nullable=false)
     */
    protected $fileSizeFormatted;

    /**
     * Image file size.
     *
     * @var int
     *
     * @Assert\Range(min = "1")
     *
     * @ORM\Column(name="file_size", type="bigint")
     */
    protected $fileSize;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Album", inversedBy="images")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id", nullable=false)
     */
    private $album;

    /**
     * Image constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = null;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Image
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return Image
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set filePath.
     *
     * @param string $filePath
     *
     * @return Image
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get filePath.
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set fileSizeFormatted.
     *
     * @param string $fileSizeFormatted
     *
     * @return Image
     */
    public function setFileSizeFormatted($fileSizeFormatted)
    {
        $this->fileSizeFormatted = $fileSizeFormatted;

        return $this;
    }

    /**
     * Get fileSizeFormatted.
     *
     * @return string
     */
    public function getFileSizeFormatted()
    {
        return $this->fileSizeFormatted;
    }

    /**
     * Set fileSize.
     *
     * @param int $fileSize
     *
     * @return Image
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * Get fileSize.
     *
     * @return int
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Set album.
     *
     * @param Album|null $album
     *
     * @return Image
     */
    public function setAlbum($album)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get album.
     *
     * @return Album
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File $file Upload Image
     *
     * @return Image $this
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }
}
