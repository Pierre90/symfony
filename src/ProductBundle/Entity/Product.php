<?php

namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\ProductRepository")
 */
class Product
{
    public function __construct()
    {

        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }
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
     * @ORM\Column(name="view", type="integer",options={"default" : 0})
     */
    private $views;

    public function getViews()
    {
        return $this->views;
    }

    public function setViews($views)
    {
        $this->views = $views;
    }
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="products", cascade="persist" )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $user;

    /**
     * @var Category $category
     * @ORM\ManyToOne(targetEntity="ProductBundle\Entity\Category", inversedBy="products", cascade="persist" )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $category;


    /**
     * @var integer
     * @ORM\Column(name="startingPrice", type="integer")
     * @Assert\Range(min=1)
     */
    private $startingPrice;

    public function getStartingPrice()
    {
        return $this->startingPrice;
    }

    public function setStartingPrice($startingPrice)
    {
        $this->startingPrice = $startingPrice;
        return $this;
    }
    /**
     * @var integer
     * @ORM\Column(name="minimumBid", type="integer")
     * @Assert\Range(min=1)
     */
    private $minimumBid;

    public function getMinimumBid()
    {
        return $this->minimumBid;
    }

    public function setMinimumBid($minimumBid)
    {
        $this->minimumBid = $minimumBid;
        return $this;
    }
    /**
     * @var integer
     * @ORM\Column(name="maximumBid", type="integer", nullable= true)
     * @Assert\Range(min=1)
     */
    private $maximumBid;

    public function getMaximumBid()
    {
        return $this->maximumBid;
    }

    public function setMaximumBid($maximumBid)
    {
        $this->maximumBid = $maximumBid;
        return $this;
    }
    /**
     * @var integer
     * @ORM\Column(name="endDate", type="date", nullable=true)
     * @Assert\LessThanOrEqual("+1 month")
     * @Assert\GreaterThanOrEqual("+1 day")
     */
    private $endDate;

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }
    /**
     * @var User $buyer
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="boughtProducts", cascade="persist" )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $buyer;

    public function getBuyer()
    {
        return $this->buyer;
    }

    public function setBuyer($buyer)
    {
        $this->buyer = $buyer;
        return $this;
    }
    /**
    * @ORM\OneToMany(targetEntity="Bid", mappedBy="product")
     */
    private $bids;

    public function addBid(\ProductBundle\Entity\Bid $bid)
    {
        //$this->products[] = $product;

        if (!$this->bids->contains($bid)) {
            $this->bids->add($bid);
        }
        return $this;
    }

    public function removeBid(\ProductBundle\Entity\Bid $bid)
    {
        $this->bids->removeElement($bid);
    }

    public function removeAllBids()
    {
        foreach($this->bids as $bid)
        {
            $this->removeBid($bid);
        }
    }

    public function getBids()
    {
        return $this->bids;
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
     * Set title
     *
     * @param string $title
     *
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Product
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Set category
     *
     * @param \ProductBundle\Entity\Category $category
     *
     * @return Product
     */
    public function setCategory(\ProductBundle\Entity\Category $category = null)
    {
        $this->category = $category;
        $category->addProduct($this);
        return $this;
    }

    /**
     * Get category
     *
     * @return \ProductBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function getAllNotes($repository)
    {
       // $repository = $this->getDoctrine()->getRepository(Note_product::class);
        $notes = $repository->findBy(array("idProduct" => $this->id));
        return $notes;

    }

    public function getMoyenne($notes)
    {
        $moy = 0;
        foreach($notes as $note)
        {
            $moy += $note->getNote();
        }
        if(count($notes) != 0)
            $moy = $moy/count($notes);

        return $moy;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {

        // check if the name is actually a fake name
        if ($this->maximumBid != null && ($this->maximumBid < $this->startingPrice+$this->minimumBid) )
        {
            $context->buildViolation('Le prix d\' doit être supérieur au prix de départ plus l\'enchère minimum.')
                ->atPath('maximumBid')
                ->addViolation();
        }
        if($this->endDate == null && $this->maximumBid == null)
        {
            $context->buildViolation('Vous devez renseigner soit un prix d\'achat soit une date de fin pour l\'enchère')
                ->atPath('maximumBid')
                ->addViolation();
        }
    }
}
