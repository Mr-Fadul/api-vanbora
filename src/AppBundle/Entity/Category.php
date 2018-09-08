<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Service\UploadService;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Category
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
     * @ORM\Column(name="description", type="string", length=1200, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

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
     * Set photo
     *
     * @param string $photo
     *
     * @return Category
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
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
    const UPLOAD_PATH_USER_PHOTO = 'uploads/category/photo';

    // 512000  bytes / 500 kbytes
    // 1048576 bytes / 1024 kbytes
    // 2097152 bytes / 2048 kbytes

    /**
     * @Assert\File(
     *     maxSizeMessage = "O tamanho da imagem é muito grande ({{ size }} {{ suffix }}), escolha uma imagem de até {{ limit }} {{ suffix }}",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *     mimeTypesMessage = "Formato de arquivo inválido. Formatos permitidos: .gif, .jpeg e .png"
     * )
     */
    private $photoTemp;

    /**
     * Sets photoTemp
     *
     * @param UploadedFile $photoTemp
     */
    public function setPhotoTemp(UploadedFile $photoTemp = null)
    {
        $this->photoTemp = $photoTemp;
    }

    /**
     * Get photoTemp
     *
     * @return UploadedFile
     */
    public function getPhotoTemp()
    {
        return $this->photoTemp;
    }

    /**
     * Unlink Photo (Apagar foto quando excluir objeto)
     */
    public function unlinkPhotos()
    {
        if ($this->getPhoto() != null) {
            unlink(Category::UPLOAD_PATH_USER_PHOTO ."/". $this->getPhoto());
        }
    }

    public function uploadPhoto()
    {
        //Upload de foto de usuário
        if($this->getPhotoTemp()!=null){
          //Se o diretorio não existir, cria
          if (!file_exists(Category::UPLOAD_PATH_USER_PHOTO)) {
              mkdir(Category::UPLOAD_PATH_USER_PHOTO, 0755, true);
          }
          if(
              ($this->getPhotoTemp() != $this->getPhoto())
              && (null !== $this->getPhoto())
          ){
              unlink(Category::UPLOAD_PATH_USER_PHOTO ."/". $this->getPhoto());
          }

          // Gera um nome único para o arquivo
          $fileName = md5(uniqid()).'.'.$this->getPhotoTemp()->guessExtension();

          UploadService::compress($this->getPhotoTemp(), Category::UPLOAD_PATH_USER_PHOTO."/".$fileName, 100);

          $this->photo = $fileName;
          $this->setPhotoTemp(null);
        }

    }

    /**
     * Lifecycle callback to upload the file to the server
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function lifecycleFileUpload() {
        $this->uploadPhoto();
    }

    /**
     * @ORM\PostLoad()
     */
    public function postLoad()
    {
        $this->dtUpdate = new \DateTime();
    }
}