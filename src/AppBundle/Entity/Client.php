<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @return float|int
     */
    public function getDiscount()
    {
        if (intval($this->discount) == $this->discount) {
            return (int) $this->discount;
        }
        return $this->discount;
    }

    /**
     * @param float $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }
}

