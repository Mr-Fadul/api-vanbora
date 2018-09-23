<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Service\UploadService;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * DocumentDriver
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="document_driver")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DocumentDriverRepository")
 * @ExclusionPolicy("all")
 */
class DocumentDriver
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="cnh_image", type="string", length=255, nullable=true)
     */
    private $cnhImage;

    /**
     * @var string
     *
     * @ORM\Column(name="crlv_image", type="string", length=255, nullable=true)
     */
    private $crlvImage;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="is_pending", type="boolean", nullable=true)
     */
    private $isPending;

    /**
     * @var string
     *
     * @ORM\Column(name="pending_description", type="string", length=255, nullable=true)
     */
    private $pendingDescription;

    /**
     * @var DocumentDriver
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Driver")
     * @ORM\JoinColumn(name="driver_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $driver;

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
     * Set cnhImage
     *
     * @param string $cnhImage
     * @return DocumentDriver
     */
    public function setCnhImage($cnhImage)
    {
        $this->cnhImage = $cnhImage;

        return $this;
    }

    /**
     * Get cnhImage
     *
     * @return string
     */
    public function getCnhImage()
    {
        return $this->cnhImage;
    }

    /**
     * Set crlvImage
     *
     * @param string $crlvImage
     * @return DocumentDriver
     */
    public function setCrlvImage($crlvImage)
    {
        $this->crlvImage = $crlvImage;

        return $this;
    }

    /**
     * Get crlvImage
     *
     * @return string
     */
    public function getCrlvImage()
    {
        return $this->crlvImage;
    }


    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return DocumentDriver
     */
    public function setStatus($status){
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus(){
        return $this->status;
    }

    /**
     * Set isPending
     *
     * @param boolean $isPending
     *
     * @return DocumentDriver
     */
    public function setIsPending($isPending){
        $this->isPending = $isPending;

        return $this;
    }

    /**
     * Get isPending
     *
     * @return boolean
     */
    public function getIsPending(){
        return $this->isPending;
    }

    /**
     * Set pendingDescription
     *
     * @param string $pendingDescription
     * @return DocumentDriver
     */
    public function setPendingDescription($pendingDescription)
    {
        $this->pendingDescription = $pendingDescription;

        return $this;
    }

    /**
     * Get pendingDescription
     *
     * @return string
     */
    public function getPendingDescription()
    {
        return $this->pendingDescription;
    }

    /**
     * Set driver
     *
     * @param Driver $driver
     *
     * @return DocumentDriver
     */
    public function setDriver(Driver $driver = null)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Get driver
     *
     * @return DocumentDriver
     */
    public function getDriver()
    {
        return $this->driver;
    }
    
    /**
     * Upload image
     */

    // Constante com o caminho para salvar a imagem screenshot
    const UPLOAD_PATH_DOCUMENT_DRIVER = 'uploads/documentsdriver/photo';

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
    private $cnhImageTemp;

    /**
     * @Assert\File(
     *     maxSize = "3072k",
     *     maxSizeMessage = "O tamanho da imagem é muito grande ({{ size }} {{ suffix }}), escolha uma imagem de até {{ limit }} {{ suffix }}",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *     mimeTypesMessage = "Formato de arquivo inválido. Formatos permitidos: .gif, .jpeg e .png"
     * )
     */
    private $crlvImageTemp;

    /**
     * Sets cnhImageTemp
     *
     * @param UploadedFile $cnhImageTemp
     */
    public function setCnhImageTemp(UploadedFile $cnhImageTemp = null)
    {
        $this->cnhImageTemp = $cnhImageTemp;
    }

    /**
     * Get cnhImageTemp
     *
     * @return UploadedFile
     */
    public function getCnhImageTemp()
    {
        return $this->cnhImageTemp;
    }

    /**
     * Sets crlvImageTemp
     *
     * @param UploadedFile $crlvImageTemp
     */
    public function setCrlvImageTemp(UploadedFile $crlvImageTemp = null)
    {
        $this->crlvImageTemp = $crlvImageTemp;
    }

    /**
     * Get crlvImageTemp
     *
     * @return UploadedFile
     */
    public function getCrlvImageTemp()
    {
        return $this->crlvImageTemp;
    }

    /**
     * Unlink Photo (Apagar foto quando excluir objeto)
     */
    public function unlinkImages()
    {
        if ($this->getCnhImage() != null) {
            unlink(DocumentDriver::UPLOAD_PATH_DOCUMENT_DRIVER ."/". $this->getCnhImage());
        }
        if ($this->getCrlvImage() != null) {
            unlink(DocumentDriver::UPLOAD_PATH_DOCUMENT_DRIVER ."/". $this->getCrlvImage());
        }
    }

    public function uploadImage()
    {

        //Upload de cnh
        if ($this->getCnhImageTemp()!=null) {
            //Se o diretorio não existir, cria
            if (!file_exists(DocumentDriver::UPLOAD_PATH_DOCUMENT_DRIVER)) {
                mkdir(DocumentDriver::UPLOAD_PATH_DOCUMENT_DRIVER, 0755, true);
            }
            if (
              ($this->getCnhImageTemp() != $this->getCnhImage())
              && (null !== $this->getCnhImage())
          ) {
                unlink(DocumentDriver::UPLOAD_PATH_DOCUMENT_DRIVER ."/". $this->getCnhImage());
            }

            // Gera um nome único para o arquivo
            $fileName = md5(uniqid()).'.'.$this->getCnhImageTemp()->guessExtension();

            UploadService::compress($this->getCnhImageTemp(), DocumentDriver::UPLOAD_PATH_DOCUMENT_DRIVER."/".$fileName, 100);

            $this->cnhImage = $fileName;
            $this->setCnhImageTemp(null);
        }

        //Upload de crlv
        if ($this->getCrlvImageTemp()!=null) {
            //Se o diretorio não existir, cria
            if (!file_exists(DocumentDriver::UPLOAD_PATH_DOCUMENT_DRIVER)) {
                mkdir(DocumentDriver::UPLOAD_PATH_DOCUMENT_DRIVER, 0755, true);
            }
            if (
              ($this->getCrlvImageTemp() != $this->getCrlvImage())
              && (null !== $this->getCrlvImage())
          ) {
                unlink(DocumentDriver::UPLOAD_PATH_DOCUMENT_DRIVER ."/". $this->getCrlvImage());
            }

            // Gera um nome único para o arquivo
            $fileName = md5(uniqid()).'.'.$this->getCrlvImageTemp()->guessExtension();

            UploadService::compress($this->getCrlvImageTemp(), DocumentDriver::UPLOAD_PATH_DOCUMENT_DRIVER."/".$fileName, 100);

            $this->crlvImage = $fileName;
            $this->setCrlvImageTemp(null);
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