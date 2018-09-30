<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coupon
 *
 * @ORM\Table(name="coupon")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CouponRepository")
 */
class Coupon
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
     * @var string
     *
     * @ORM\Column(name="promotionalCode", type="string", length=255)
     */
    private $promotionalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="typeValue", type="string", length=255)
     */
    private $typeValue;

    /**
     * @var string
     *
     * @ORM\Column(name="monetaryValue", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $monetaryValue;

    /**
     * @var int
     *
     * @ORM\Column(name="percentValue", type="integer", nullable=true)
     */
    private $percentValue;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expirationDate", type="date")
     */
    private $expirationDate;

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
     * Set promotionalCode
     *
     * @param string $promotionalCode
     *
     * @return Coupom
     */
    public function setPromotionalCode($promotionalCode)
    {
        $this->promotionalCode = $promotionalCode;

        return $this;
    }

    /**
     * Get promotionalCode
     *
     * @return string
     */
    public function getPromotionalCode()
    {
        return $this->promotionalCode;
    }

    /**
     * Set typeValue
     *
     * @param string $typeValue
     *
     * @return Coupom
     */
    public function setTypeValue($typeValue)
    {
        $this->typeValue = $typeValue;

        return $this;
    }

    /**
     * Get typeValue
     *
     * @return string
     */
    public function getTypeValue()
    {
        return $this->typeValue;
    }

    /**
     * Set monetaryValue
     *
     * @param string $monetaryValue
     *
     * @return Coupom
     */
    public function setMonetaryValue($monetaryValue)
    {
        $this->monetaryValue = $monetaryValue;

        return $this;
    }

    /**
     * Get monetaryValue
     *
     * @return string
     */
    public function getMonetaryValue()
    {
        return $this->monetaryValue;
    }

    /**
     * Set percentValue
     *
     * @param integer $percentValue
     *
     * @return Coupom
     */
    public function setPercentValue($percentValue)
    {
        $this->percentValue = $percentValue;

        return $this;
    }

    /**
     * Get percentValue
     *
     * @return integer
     */
    public function getPercentValue()
    {
        return $this->percentValue;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Coupom
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set expirationDate
     *
     * @param \DateTime $expirationDate
     *
     * @return Coupom
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * Get expirationDate
     *
     * @return \DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }
}
