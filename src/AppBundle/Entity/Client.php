<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 * @ExclusionPolicy("all")
 */
class Client extends User
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
     * @ORM\Column(name="street", type="string", length=255, nullable=true)
     * @Expose
     */
    private $street;
    
    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=255, nullable=true)
     * @Expose
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="string", length=255, nullable=true)
     * @Expose
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     * @Expose
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="neighborhood", type="string", length=255, nullable=true)
     * @Expose
     */
    private $neighborhood;

    /**
     * @var string
     *
     * @ORM\Column(name="date_of_birth", type="string", length=255, nullable=true)
     * @Expose
     */
    private $dateOfBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255, nullable=true)
     * @Expose
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     * @Expose
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="cpf", type="string", length=255, nullable=true)
     * @Expose
     */
    private $cpf;


    /**
     * Set street
     *
     * @param string $street
     *
     * @return Client
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return Client
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     *
     * @return Client
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Client
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set neighborhood
     *
     * @param string $neighborhood
     *
     * @return Client
     */
    public function setNeighborhood($neighborhood)
    {
        $this->neighborhood = $neighborhood;

        return $this;
    }

    /**
     * Get neighborhood
     *
     * @return string
     */
    public function getNeighborhood()
    {
        return $this->neighborhood;
    }

    /**
     * Set dateOfBirth
     *
     * @param string $dateOfBirth
     *
     * @return Client
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return string
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Client
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Client
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

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
