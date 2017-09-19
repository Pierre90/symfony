<?php
/**
 * Created by PhpStorm.
 * User: pierre
 * Date: 9/18/17
 * Time: 10:42 AM
 */

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
/**
 * Class User
 * @package UserBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @var
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Product $products
     * @ORM\OneToMany(targetEntity="ProductBundle\Entity\Product", mappedBy="user")
     */
    private $products;

    /**
     *
     * @ORM\Column(type="string")
     *
     */
    private $firstName;


    /**
     * @ORM\Column(type="string")
     */
    private $lastName;



    public function __construct()
    {
        parent::__construct();
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return this;
    }



    public function __toString()
    {
        return $this->getUsername();
    }

}