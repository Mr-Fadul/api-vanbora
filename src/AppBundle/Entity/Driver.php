<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Driver
 *
 * @ORM\Table(name="driver")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DriverRepository")
 * @ExclusionPolicy("all")
 */
class Driver extends User
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
     * @ORM\Column(name="cpf", type="string", length=255, nullable=true)
     * @Expose
     */
    private $cpf;

    /**
     * Set cpf
     *
     * @param string $cpf
     *
     * @return Client
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * Get cpf
     *
     * @return string
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    public function formatCpf()
    {
        if (!$this->cpf) {
            return '';
        }

        $cpf = substr($this->cpf, 0, 3) . '.';
        $cpf .= substr($this->cpf, 3, 3) . '.';
        $cpf .= substr($this->cpf, 6, 3) . '-';
        $cpf .= substr($this->cpf, 9, 2);

        return $cpf;
    }
}
