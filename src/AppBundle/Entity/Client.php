<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * Client
 *
 * @ORM\Table(name="clients")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 */
class Client
{

    const VAT_NUMBER_PREFIX = 'BG';

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
     * @ORM\Column(name="company", type="string", length=255)
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="responsible_person", type="string", length=255)
     */
    private $responsiblePerson;

    /**
     * @var string
     *
     * @ORM\Column(name="unique_identifier", type="string", length=255, unique=true)
     */
    private $uniqueIdentifier;

    /**
     * @var bool
     *
     * @ORM\Column(name="vat", type="boolean", options={"default" : 0})
     */
    private $vat;

    /**
     * Discount value percentage
     * @var float
     *
     * @ORM\Column(name="discount", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $discount;

    /**
     * @var string
     */
    private $vatNumber;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", options={"default" : 0})
     */
    private $active;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Order", mappedBy="client")
     */
    private $orders;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Report", mappedBy="client")
     */
    private $reports;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ReEntry", mappedBy="client")
     */
    private $returns;

    /**
     * @var bool
     *
     * @ORM\Column(name="private_person", type="boolean", options={"default" : 0})
     */
    private $privatePerson;

    public function __construct($privatePerson = false)
    {
        $this->orders = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->returns = new ArrayCollection();
        $this->privatePerson = $privatePerson;
        if ($privatePerson) {
            $this->setUniqueIdentifier(uniqid());
            $this->setCompany('N/A');
            $this->setActive(true);
            $this->setVat(false);
        }
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
     * Set company
     *
     * @param string $company
     *
     * @return Client
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Client
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set responsiblePerson
     *
     * @param string $responsiblePerson
     *
     * @return Client
     */
    public function setResponsiblePerson($responsiblePerson)
    {
        $this->responsiblePerson = $responsiblePerson;

        return $this;
    }

    /**
     * Get responsiblePerson
     *
     * @return string
     */
    public function getResponsiblePerson()
    {
        return $this->responsiblePerson;
    }

    /**
     * Set uniqueIdentifier
     *
     * @param string $uniqueIdentifier
     *
     * @return Client
     */
    public function setUniqueIdentifier($uniqueIdentifier)
    {
        $this->uniqueIdentifier = $uniqueIdentifier;

        return $this;
    }

    /**
     * Get uniqueIdentifier
     *
     * @return string
     */
    public function getUniqueIdentifier()
    {
        return $this->uniqueIdentifier;
    }

    /**
     * Set vat
     *
     * @param boolean $vat
     *
     * @return Client
     */
    public function setVat($vat)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Get vat
     *
     * @return bool
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @return string|null
     */
    public function getVatNumber()
    {
        if ($this->getVat() && $this->vatNumber === null) {
            $this->setVatNumber();
        }
        return $this->vatNumber;
    }

    /**
     * @return void
     */
    private function setVatNumber()
    {
        $this->vatNumber = self::VAT_NUMBER_PREFIX.$this->uniqueIdentifier;
    }

    /**
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return ArrayCollection|Order[]|Collection
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * @param ArrayCollection $orders
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return ArrayCollection|Report[]|Collection
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    /**
     * @param ArrayCollection $reports
     */
    public function setReports(ArrayCollection $reports): void
    {
        $this->reports = $reports;
    }

    /**
     * @return ArrayCollection|ReEntry[]|Collection
     */
    public function getReturns(): Collection
    {
        return $this->returns;
    }

    /**
     * @param ArrayCollection $returns
     */
    public function setReturns(ArrayCollection $returns): void
    {
        $this->returns = $returns;
    }

    public function getStocks(): array
    {
        $orders = $this->getOrders();
        $reports = $this->getReports();
        $returns = $this->getReturns();

        $products = [];
        $stocks = [];

        foreach ($orders as $order) {
            foreach ($order->getDetails() as $detail) {
                $productId = $detail->getProduct()->getId();
                if (!key_exists($productId,$stocks)) {
                    $products[$productId] = $detail->getProduct()->getTitle();
                    $stocks[$productId] = 0;
                }
                $stocks[$productId] += $detail->getQuantity();
            }
        }

        foreach ($reports as $report) {
            foreach ($report->getDetails() as $detail) {
                $productId = $detail->getProduct()->getId();
                $stocks[$productId] -= $detail->getQuantity();
            }
        }

        foreach ($returns as $return) {
            $productId = $return->getProduct()->getId();
            $stocks[$productId] -= $return->getQuantity();
        }

        $stocks = array_combine($products,$stocks);

        ksort($stocks);

        return array_filter($stocks);

    }

    public function hasOrderedProduct(Product $product): bool
    {
        foreach ($this->getOrders() as $order) {
            foreach ($order->getDetails() as $detail) {
                if ($detail->getProduct()->getId() == $product->getId()) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isPrivatePerson(): bool
    {
        return $this->privatePerson;
    }

    /**
     * @param bool $privatePerson
     */
    public function setPrivatePerson(bool $privatePerson): void
    {
        $this->privatePerson = $privatePerson;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        if ($this->isPrivatePerson()) {
            return $this->getResponsiblePerson();
        }
        return $this->getCompany();
    }

    public function getStatus()
    {
        if ($this->getActive()) {
            return 'Active';
        }
        return 'Disabled';
    }

    public function getEditPath()
    {
        if ($this->isPrivatePerson()) {
            return 'private_person_edit';
        }
        return 'clients_edit';
    }

    public function getType()
    {
        if ($this->isPrivatePerson()) {
            return 'Private';
        }
        return 'Company';
    }

}

