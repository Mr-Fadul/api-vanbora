<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WhereISpend
 *
 * @ORM\Table(name="where_i_spend")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WhereISpendRepository")
 */
class WhereISpend
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
     * @ORM\Column(name="neighborhood", type="string", length=255)
     */
    private $neighborhood;

    /**
     * @var Ad
     *
     * @ORM\ManyToOne(targetEntity="Ad", inversedBy="where_i_spend")
     * @ORM\JoinColumn(name="ad_id", referencedColumnName="id",onDelete="CASCADE", nullable=true)
     */
    private $where;


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
     * Set neighborhood
     *
     * @param string $neighborhood
     * @return WhereISpend
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
     * Set where
     *
     * @param WhereISpend $where
     *
     * @return Course
     */
    public function setWhere($where)
    {
        $this->where = $where;

        return $this;
    }

    /**
     * Get where
     *
     * @return WhereISpend
     */
    public function getWhere()
    {
        return $this->where;
    }
}
