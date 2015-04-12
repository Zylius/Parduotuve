<?php
/**
 * Created by PhpStorm.
 * User: Zylius
 * Date: 2015-04-12
 * Time: 16:23
 */

namespace Zylius\ParduotuveBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var LogItem[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="LogItem", mappedBy="user")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $logs;

    /**
     * @var Product[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Product", mappedBy="user")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $products;

    /**
     * @var ProductOrder[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ProductOrder", mappedBy="user")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $orders;

    /**
     * @var ProductOrder[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ProductOrder", mappedBy="confirmer")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $confirmations;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->logs = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->confirmations = new ArrayCollection();
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
     * Add logs
     *
     * @param LogItem $logs
     * @return User
     */
    public function addLog(LogItem $logs)
    {
        $this->logs[] = $logs;

        return $this;
    }

    /**
     * Remove logs
     *
     * @param LogItem $logs
     */
    public function removeLog(LogItem $logs)
    {
        $this->logs->removeElement($logs);
    }

    /**
     * Get logs
     *
     * @return \Doctrine\Common\Collections\Collection|LogItem[]
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * Add products
     *
     * @param Product $products
     * @return User
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
     * @return \Doctrine\Common\Collections\Collection|Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add orders
     *
     * @param ProductOrder $orders
     * @return User
     */
    public function addOrder(ProductOrder $orders)
    {
        $this->orders[] = $orders;

        return $this;
    }

    /**
     * Remove orders
     *
     * @param ProductOrder $orders
     */
    public function removeOrder(ProductOrder $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection|ProductOrder[]
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Add confirmations
     *
     * @param ProductOrder $confirmations
     * @return User
     */
    public function addConfirmation(ProductOrder $confirmations)
    {
        $this->confirmations[] = $confirmations;

        return $this;
    }

    /**
     * Remove confirmations
     *
     * @param ProductOrder $confirmations
     */
    public function removeConfirmation(ProductOrder $confirmations)
    {
        $this->confirmations->removeElement($confirmations);
    }

    /**
     * Get confirmations
     *
     * @return \Doctrine\Common\Collections\Collection|ProductOrder[]
     */
    public function getConfirmations()
    {
        return $this->confirmations;
    }
}
