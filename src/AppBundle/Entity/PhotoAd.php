<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PhotoAd
 *
 * @ORM\Table(name="photo_ad")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PhotoAdRepository")
 */
class PhotoAd
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
     * @var Ad
     *
     * @ORM\ManyToOne(targetEntity="Ad", inversedBy="photo")
     * @ORM\JoinColumn(name="ad_id", referencedColumnName="id",onDelete="CASCADE", nullable=true)
     */
    private $photo_ad;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set photo_ad
     *
     * @param WhereISpend $photo_ad
     *
     * @return PhotoAd
     */
    public function setPhotoAd($photo_ad)
    {
        $this->photo_ad = $photo_ad;

        return $this;
    }

    /**
     * Get photo_ad
     *
     * @return PhotoAd
     */
    public function getPhotoAd()
    {
        return $this->photo_ad;
    }
}
