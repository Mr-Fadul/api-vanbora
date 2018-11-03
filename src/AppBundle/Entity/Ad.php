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
     * @var string
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_online", type="boolean")
     */
    private $payment_online;

    /**
     * @var string
     *
     * @ORM\Column(name="photo_highlight", type="string", length=255, nullable=true)
     */
    private $photo_highlight;

    /**
     * @ORM\OneToMany(targetEntity="WhereISpend", mappedBy="where", cascade={"all"}, orphanRemoval=true)
     */
    private $where_i_spend;

    /**
     * @ORM\OneToMany(targetEntity="PhotoAd", mappedBy="photo_ad", cascade={"all"}, orphanRemoval=true)
     */
    private $photo;

    /**
     * @var Duvidas
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true)
     */
    private $category;

    /**
     * @var DocumentDriver
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Driver")
     * @ORM\JoinColumn(name="driver_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $driver;

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

    public function __construct(){
        $this->where_i_spend = new ArrayCollection();
        $this->photo = new ArrayCollection();
    }

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
     * Set active
     *
     * @param boolean $active
     *
     * @return Ad
     */
    public function setActive($active){
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive(){
        return $this->active;
    }

    /**
     * Set payment_online
     *
     * @param boolean $payment_online
     *
     * @return Ad
     */
    public function setPaymentOnline($payment_online){
        $this->payment_online = $payment_online;

        return $this;
    }

    /**
     * Get payment_online
     *
     * @return boolean
     */
    public function getPaymentOnline(){
        return $this->payment_online;
    }

    /**
     * Set photo_highlight
     *
     * @param string $photo_highlight
     *
     * @return Ad
     */
    public function setPhotoHighlight($photo_highlight)
    {
        $this->photo_highlight = $photo_highlight;

        return $this;
    }

    /**
     * Get photo_highlight
     *
     * @return string
     */
    public function getPhotoHighlight()
    {
        return $this->photo_highlight;
    }

    /**
     * Set where_i_spend
     *
     * @param Ad $where_i_spend
     * @return Ad
     */
    public function setWhereISpend($where_i_spend = null){
        $this->where_i_spend = $where_i_spend;

        return $this;
    }

    /**
     * Get where_i_spend
     *
     * @return Ad
     */
    public function getWhereISpend(){
        return $this->where_i_spend;
    }

    public function addWhereISpend(WhereISpend $where_i_spend){
        $where_i_spend->setWhere($this);

        $this->where_i_spend->add($where_i_spend);
    }

    public function removeWhereISpend(WhereISpend $where_i_spend){
        $this->where_i_spend->removeElement($where_i_spend);
    }

    /**
     * Set photo
     *
     * @param Ad $photo
     * @return Ad
     */
    public function setPhoto($photo = null){
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return Ad
     */
    public function getPhoto(){
        return $this->photo;
    }

    public function addPhoto(PhotoAd $photo){
        $photo->setPhotoAd($this);

        $this->photo->add($photo);
    }

    public function removePhoto(PhotoAd $photo){
        $this->photo->removeElement($photo);
    }

    /**
     *
     */
    public function setCategory($category){
        $this->category = $category;
    }

    /**
     *
     * @return type
     */
    public function getCategory(){
        return $this->category;
    }

    /**
     * Set driver
     *
     * @param Driver $driver
     *
     * @return Ad
     */
    public function setDriver(Driver $driver = null)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Get driver
     *
     * @return Ad
     */
    public function getDriver()
    {
        return $this->driver;
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

    /**
     * Upload image
     */

    // Constante com o caminho para salvar a imagem screenshot
    const UPLOAD_PATH_AD_PHOTO = 'uploads/ad/photo';

    // 512000  bytes / 500 kbytes
    // 1048576 bytes / 1024 kbytes
    // 2097152 bytes / 2048 kbytes

    /**
     * @Assert\File(
     *     maxSize = "3072k",
     *     maxSizeMessage = "O tamanho da imagem é muito grande ({{ size }} {{ suffix }}), escolha uma imagem de até {{ limit }} {{ suffix }}",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *     mimeTypesMessage = "Formato de arquivo inválido. Formatos permitidos: .gif, .jpeg e .png"
     * )
     */
    private $photo_highlight_temp;

    /**
     * Sets photo_highlight_temp
     *
     * @param UploadedFile $photo_highlight_temp
     */
    public function setPhotoHighlightTemp(UploadedFile $photo_highlight_temp = null)
    {
        $this->photo_highlight_temp = $photo_highlight_temp;
    }

    /**
     * Get photo_highlight_temp
     *
     * @return UploadedFile
     */
    public function getPhotoHighlightTemp()
    {
        return $this->photo_highlight_temp;
    }

    /**
     * Unlink Photo (Apagar foto quando excluir objeto)
     */
    public function unlinkImages()
    {
        if ($this->getPhotoHighlight() != null) {
            unlink(Ad::UPLOAD_PATH_AD_PHOTO ."/". $this->getPhotoHighlight());
        }
    }

    public function uploadImage()
    {

        //Upload de foto destaque
        if ($this->getPhotoHighlightTemp()!=null) {
            //Se o diretorio não existir, cria
            if (!file_exists(Ad::UPLOAD_PATH_AD_PHOTO)) {
                mkdir(Ad::UPLOAD_PATH_AD_PHOTO, 0755, true);
            }
            if (
              ($this->getPhotoHighlightTemp() != $this->getPhotoHighlight())
              && (null !== $this->getPhotoHighlight())
          ) {
                unlink(Ad::UPLOAD_PATH_AD_PHOTO ."/". $this->getPhotoHighlight());
            }

            // Gera um nome único para o arquivo
            $fileName = md5(uniqid()).'.'.$this->getPhotoHighlightTemp()->guessExtension();

            UploadService::compress($this->getPhotoHighlightTemp(), Ad::UPLOAD_PATH_AD_PHOTO."/".$fileName, 100);

            $this->photo_highlight = $fileName;
            $this->setPhotoHighlightTemp(null);
        }
    }

    /**
     * Lifecycle callback to upload the file to the server
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function lifecycleFileUpload()
    {
        $this->uploadImage();
    }

    /**
     * @ORM\PostLoad()
     */
    public function postLoad()
    {
        $this->dtUpdate = new \DateTime();
    }
}