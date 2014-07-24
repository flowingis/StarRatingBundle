<?php

namespace Ideato\StarRatingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rating
 *
 * @ORM\Entity
 * @ORM\Table(name="isr_rating")
 */
class Rating
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * 
     *
     * @ORM\Column(name="average", type="decimal", scale=2)
     */
    private $average;

    /**
     *
     *
     * @ORM\Column(name="total_count", type="decimal", scale=2)
     */
    private $totalcount;

    /**
     *
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;


    /**
     * Set id
     *
     * @param $id
     * @return Rating
     */
    public function setId( $id )
    {
        $this->id = $id;

        return $this;
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
     * Set average
     *
     * @param string $average
     * @return Rating
     */
    public function setAverage($average)
    {
        $this->average = $average;

        return $this;
    }

    /**
     * Get average
     *
     * @return string 
     */
    public function getAverage()
    {
        return $this->average;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return Rating
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }


    /**
     * Set totalcount
     *
     * @param string $totalcount
     * @return Rating
     */
    public function setTotalcount($totalcount)
    {
        $this->totalcount = $totalcount;

        return $this;
    }

    /**
     * Get totalcount
     *
     * @return string 
     */
    public function getTotalcount()
    {
        return $this->totalcount;
    }
}
