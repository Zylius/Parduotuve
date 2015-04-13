<?php

namespace Zylius\ParduotuveBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProductOrder
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Zylius\ParduotuveBundle\Entity\ProductOrderRepository")
 */
class ProductOrder
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders")
     */
    private $user;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="confirmations")
     */
    private $confirmer;

    /**
     * @var Product[]
     *
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="orders")
     * @ORM\JoinTable(
     *      name="order_product",
     *      joinColumns={@ORM\JoinColumn(name="order_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")}
     * )
     */
    private $products;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
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
     * Set date
     *
     * @param \DateTime $date
     * @return ProductOrder
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return ProductOrder
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set confirmer
     *
     * @param User $confirmer
     * @return ProductOrder
     */
    public function setConfirmer(User $confirmer = null)
    {
        $this->confirmer = $confirmer;

        return $this;
    }

    /**
     * Get confirmer
     *
     * @return User 
     */
    public function getConfirmer()
    {
        return $this->confirmer;
    }

    /**
     * Add products
     *
     * @param Product $products
     * @return ProductOrder
     */
    public function addProduct(Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param Product $products
     */
    public function removeProduct(Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return Collection|Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }
}
