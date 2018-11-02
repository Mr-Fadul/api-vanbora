<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Service\UploadService;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Ad
 *
 * @ORM\Table(name="ad")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AdRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Ad
{
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="short_description", type="string", length=255)
     */
    private $short_description;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1200, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="monthly", type="boolean")
     */
    private $monthly;

    /**
     * @var Duvidas
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true)
     */
    private $category;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_creation", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $dtCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_update", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $dtUpdate;

    public function __toString() {
        return $this->name;
    }

    /**
     * Set id
     *
     * @param integer $id;
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Course
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set short_description
     *
     * @param string $short_description
     * @return Course
     */
    public function setShortDescription($short_description)
    {
        $this->short_description = $short_description;

        return $this;
    }

    /**
     * Get short_description
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->short_description;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Course
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Course
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Ad
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }


    /**
     * Get price
     *
     * @return decimal
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set monthly
     *
     * @param boolean $monthly
     *
     * @return Ad
     */
    public function setMonthly($monthly){
        $this->monthly = $monthly;

        return $this;
    }

    /**
     * Get monthly
     *
     * @return boolean
     */
    public function getMonthly(){
        return $this->monthly;
    }

    /**
     * Set category
     *
     * @param Category $category
     * @return Category
     */
    public function setCategory($category = null){
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory(){
        return $this->category;
    }

    /**
     * Set dtCreation
     *
     * @param \DateTime $dtCreation
     *
     * @return Banner
     */
    public function setDtCreation($dtCreation)
    {
        $this->dtCreation = $dtCreation;

        return $this;
    }

    /**
     * Get dtCreation
     *
     * @return \DateTime
     */
    public function getDtCreation()
    {
        return $this->dtCreation;
    }

    /**
     * Set dtUpdate
     *
     * @param \DateTime $dtUpdate
     *
     * @return Banner
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Get dtUpdate
     *
     * @return \DateTime
     */
    public function getDtUpdate()
    {
        return $this->dtUpdate;
    }
}