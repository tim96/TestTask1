<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Album.
 *
 * @ORM\Table(name="project_album")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AlbumRepository")
 */
class Album
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
     * The album name.
     *
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     *
     * @ORM\Column(name="album_name", type="string", length=255)
     */
    protected $albumName;

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
     * @var Image[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Image", mappedBy="album", cascade={"persist"})
     */
    private $images;

    /**
     * Count image files.
     *
     * @var int
     *
     * @Assert\Range(min = "0")
     *
     * @ORM\Column(name="count_images", type="integer", options={"default": 0})
     */
    protected $countImages;

    /**
     * Album constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = null;
        $this->images = new ArrayCollection();
        $this->countImages = 0;
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
     * @return Album
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
     * @return Album
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
     * Set albumName.
     *
     * @param string $albumName
     *
     * @return Album
     */
    public function setAlbumName($albumName)
    {
        $this->albumName = $albumName;

        return $this;
    }

    /**
     * Get albumName.
     *
     * @return string
     */
    public function getAlbumName()
    {
        return $this->albumName;
    }

    /**
     * Add images.
     *
     * @param \AppBundle\Entity\Image $images
     *
     * @return Album
     */
    public function addImage(\AppBundle\Entity\Image $images)
    {
        $this->images[] = $images;
        $images->setAlbum($this);

        $this->setCountImages(count($this->images));

        return $this;
    }

    /**
     * Remove images.
     *
     * @param \AppBundle\Entity\Image $images
     */
    public function removeImage(\AppBundle\Entity\Image $images)
    {
        $this->images->removeElement($images);
        $images->setAlbum(null);

        $this->setCountImages(count($this->images));
    }

    /**
     * Get images.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set countImages.
     *
     * @param int $countImages
     *
     * @return Album
     */
    public function setCountImages($countImages)
    {
        $this->countImages = $countImages;

        return $this;
    }

    /**
     * Get countImages.
     *
     * @return int
     */
    public function getCountImages()
    {
        return $this->countImages;
    }
}
