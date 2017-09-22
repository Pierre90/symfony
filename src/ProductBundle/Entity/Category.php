<?php

namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;


    /**
     * @var Product $products
     * @ORM\OneToMany(targetEntity="ProductBundle\Entity\Product", mappedBy="category")
     */
    private $products;

    /**
     * @var Category $parent
     * @ORM\ManyToOne(targetEntity="ProductBundle\Entity\Category", inversedBy="children")
     * @ORM\JoinColumn(nullable=true, onDelete="cascade")
     */
    private $parent;

    /**
     * @var Category $children
     * @ORM\OneToMany(targetEntity="ProductBundle\Entity\Category", mappedBy="parent")
     */
    private $children;
    /**
     * @return Category
     */

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }


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
     * Set name
     *
     * @param string $name
     *
     * @return Category
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

    public function __toString()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add product
     *
     * @param \ProductBundle\Entity\Product $product
     *
     * @return Category
     */
    public function addProduct(\ProductBundle\Entity\Product $product)
    {
        //$this->products[] = $product;

        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }
        return $this;
    }

    /**
     * Remove product
     *
     * @param \ProductBundle\Entity\Product $product
     */
    public function removeProduct(\ProductBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    public function addChildren(\ProductBundle\Entity\Category $children)
    {
        $this->children[] = $children;
    }

    public function removeChildren(\ProductBundle\Entity\Category $children)
    {
        $this->children->removeElement($children);

    }

    public function getChildren()
    {
        return $this->children;
    }
}
