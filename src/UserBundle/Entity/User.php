<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use FOS\UserBundle\Model\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Service\UploadService;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"user" = "User", "client" = "AppBundle\Entity\Client"})
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 * @ExclusionPolicy("all")
 */
class User extends BaseUser implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=100, nullable=true)
     * @Expose
     */
    private $first_name;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=100, nullable=true)
     * @Expose
     */
    private $last_name;

    /**
     * @var string
     *
     * @ORM\Column(name="celphone", type="string", length=255, nullable=true)
     * @Expose
     */
    private $celphone;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     * @Expose
     */
    private $phone;

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

    /**
     * @var integer
     *
     * @ORM\Column(name="session_id", type="string", length=255, nullable=true)
     */
    private $session_id;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @var string $facebookId
     *
     * @ORM\Column(name="facebookId", type="string", length=100, nullable=true)
     *
     */
    protected $facebookId;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * __toString method
     */
    public function __toString()
    {
        return $this->getFullName();
    }

    /**
     * Retorna o nome completo.
     * @return string Nome completo.
     */
    public function getFullName()
    {
        return $this->getFirstName()." ".$this->getLastName();
    }

    /**
     * Set id
     *
     * @param integer $id
     */
    public function setId($id)
    {
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
     * Set first_name
     *
     * @param string $first_name
     * @return User
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * Get first_name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set last_name
     *
     * @param string $last_name
     * @return User
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * Get last_name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set celphone
     *
     * @param string $celphone
     * @return User
     */
    public function setCelphone($celphone)
    {
        $this->celphone = $celphone;

        return $this;
    }

    /**
     * Get celphone
     *
     * @return string
     */
    public function getCelphone()
    {
        return $this->celphone;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Get enabled
     *
     * Retorna o Status do Usuário(habilidado,desabilitado)
     *
     */
    public function getStatus()
    {
        $status = $this->enabled;

        if ($status == 1) {
            $status = "Habilitado";
        } else {
            $status = "Desabilitado";
        }

        return $status;
    }

    /**
     * Set dtCreation
     *
     * @param \DateTime $dtCreation
     *
     * @return Ebook
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
     * @return Ebook
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
     * Set session_id
     *
     * @param int $session_id
     * @return User
     */
    public function setSessionId($session_id)
    {
        $this->session_id = $session_id;

        return $this;
    }

    /**
     * Get session_id
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->session_id;
    }

    /**
     * Set photo
     *
     * @param string $photo
     * @return User
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
     * Upload image
     */

    // Constante com o caminho para salvar a imagem screenshot
    const UPLOAD_PATH_USER_PHOTO = 'uploads/user/photo';

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
    public function unlinkImages()
    {
        if ($this->getPhoto() != null) {
            unlink(User::UPLOAD_PATH_USER_PHOTO ."/". $this->getPhoto());
        }
    }

    public function uploadImage()
    {

        //Upload de foto de usuário
        if ($this->getPhotoTemp()!=null) {
            //Se o diretorio não existir, cria
            if (!file_exists(User::UPLOAD_PATH_USER_PHOTO)) {
                mkdir(User::UPLOAD_PATH_USER_PHOTO, 0755, true);
            }
            if (
              ($this->getPhotoTemp() != $this->getPhoto())
              && (null !== $this->getPhoto())
          ) {
                unlink(User::UPLOAD_PATH_USER_PHOTO ."/". $this->getPhoto());
            }

            // Gera um nome único para o arquivo
            $fileName = md5(uniqid()).'.'.$this->getPhotoTemp()->guessExtension();

            UploadService::compress($this->getPhotoTemp(), User::UPLOAD_PATH_USER_PHOTO."/".$fileName, 100);

            $this->photo = $fileName;
            $this->setPhotoTemp(null);
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

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    }

    public function serialize()
    {
        return serialize(array($this->facebookId, parent::serialize()));
    }

    public function unserialize($data)
    {
        list($this->facebookId, $parentData) = unserialize($data);
        parent::unserialize($parentData);
    }
    /**
     * @param Array
     */
    public function setFBData($fbdata)
    {
        if (isset($fbdata['id'])) {
            $this->setFacebookId($fbdata['id']);
            $this->addRole('ROLE_USER');
        }
        if (isset($fbdata['first_name'])) {
            $this->setFirstname($fbdata['first_name']);
        }
        if (isset($fbdata['last_name'])) {
            $this->setLastname($fbdata['last_name']);
        }
        if (isset($fbdata['email'])) {
            $this->setEmail($fbdata['email']);
        } else {
            $this->setEmail($fbdata['id']);
        }
    }
}
