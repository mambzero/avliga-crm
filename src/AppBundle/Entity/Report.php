<?php

namespace AppBundle\Entity;

use AppBundle\Entity\_Interface\HistoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Report
 *
 * @ORM\Table(name="reports")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReportRepository")
 */
class Report implements HistoryInterface
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Client", inversedBy="reports")
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ReportDetail", mappedBy="report", cascade={"persist"}, orphanRemoval=true)
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
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     *
     * @return Report
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
    public function getDateAdded(): \DateTime
    {
        return $this->dateAdded;
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
        $details->uasort(function (ReportDetail $a, ReportDetail $b) {
            return $a->getProduct()->getTitle() <=> $b->getProduct()->getTitle();
        });

        return $details;
    }

    /**
     * @var ArrayCollection
     */
    public function setDetails($details): void
    {
        $this->details = $details;
    }

    /**
     * @param ReportDetail $detail
     */
    public function addDetail(ReportDetail $detail): void
    {
        $detail->setReport($this);
        $this->details[] = $detail;
    }

    public function removeDetail(ReportDetail $reportDetail)
    {
        if ($this->details->contains($reportDetail)) {
            $this->details->removeElement($reportDetail);
        }
    }

    public function hasDetailWithProduct(Product $product): bool
    {
        foreach ($this->getDetails() as $detail) {
            if ($product->getId() === $detail->getProduct()->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return Client
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @return int
     */
    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    /**
     * @param int $clientId
     */
    public function setClientId(int $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getDetails() as $detail) {
            $total += (($detail->getPrice() * $detail->getQuantity()) * ((100 - $detail->getDiscount())/100));
        }

        return number_format($total, 2 , '.', '');
    }

    public function getProductsCount(): int
    {
        $details =  $this->getDetails()->toArray();
        $quantity = array_reduce($details, function($q, ReportDetail $detail){
            return $q = $q + $detail->getQuantity();
        }, 0);
        return $quantity;
    }

    public function getActivityType(): string
    {
        return 'Report';
    }

}

