<?php

namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bid
 *
 * @ORM\Table(name="bid")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\BidRepository")
 */
class Bid
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
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="bids")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="ProductBundle\Entity\Product", inversedBy="bids")
     * @ORM\JoinColumn(name="id_product", referencedColumnName="id")
     */
    private $product;

    /**
     * @var int
     *
     * @ORM\Column(name="bid", type="integer")
     */
    private $bid;


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
     * Set idUser
     *
     * @param integer $idUser
     *
     * @return Bid
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set idProduct
     *
     * @param integer $idProduct
     *
     * @return Bid
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get idProduct
     *
     * @return int
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set bid
     *
     * @param integer $bid
     *
     * @return Bid
     */
    public function setBid($bid)
    {
        $this->bid = $bid;

        return $this;
    }

    /**
     * Get bid
     *
     * @return int
     */
    public function getBid()
    {
        return $this->bid;
    }

    public function to_string()
    {
        return $this->bid;
    }
}

