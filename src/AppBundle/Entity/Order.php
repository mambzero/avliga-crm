<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderRepository")
 */
class Order
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
     * @ORM\Column(name="client_id", type="integer")
     */
    private $clientId;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Client", inversedBy="orders")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $dateAdded;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OrderDetail", mappedBy="order", cascade={"persist"})
     */
    private $details;

    public function __construct()
    {
        $this->dateAdded = new \DateTime('now');
        $this->details = new ArrayCollection();
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
     * Set clientId
     *
     * @param integer $clientId
     *
     * @return Order
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get clientId
     *
     * @return int
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     *
     * @return Order
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * @return Order
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @return ArrayCollection
     */
    public function getDetailsSorted()
    {
        $details = $this->details->getIterator();
        $details->uasort(function (OrderDetail $a, OrderDetail $b) {
            return $a->getProduct()->getTitle() <=> $b->getProduct()->getTitle();
        });

        return $details;
    }

    /**
     * @param OrderDetail $detail
     * @return Order
     */
    public function addDetail(OrderDetail $detail)
    {
        $detail->setOrder($this);
        $this->details[] = $detail;

        return $this;
    }

    /**
     * Return Total Sum of order.
     * @return float
     */
    public function getTotal()
    {
        $total = 0;

        foreach ($this->getDetails() as $detail) {
            $total += (($detail->getPrice() * $detail->getQuantity()) * ((100 - $detail->getDiscount())/100));
        }

        return number_format($total, 2 , '.', '');
    }
}

