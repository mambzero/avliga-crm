<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Ldap\Adapter\CollectionInterface;

/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
{
    const BOOK = 1;
    const E_BOOK = 2;
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="isbn", type="string", length=255)
     */
    private $isbn;

    /**
     * @var string
     *
     * @ORM\Column(name="imageUrl", type="string", length=255)
     */
    private $imageUrl;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer", options={"default" : 1})
     */
    private $type;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", options={"default" : 0})
     */
    private $active;

    /**
     * @var UploadedFile|string
     */
    private $image;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Entry", mappedBy="product")
     */
    private $entries;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OrderDetail", mappedBy="product")
     */
    private $orderDetails;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ReportDetail", mappedBy="product")
     */
    private $reportDetails;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ReEntry", mappedBy="product")
     */
    private $returns;

    public function __construct()
    {
        $this->entries = new ArrayCollection();
        $this->orderDetails = new ArrayCollection();
        $this->reportDetails = new ArrayCollection();
        $this->returns = new ArrayCollection();
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
        if ($this->isEBook()) {
            return $this->title.' (e-book)';
        }
        return $this->title;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set isbn
     *
     * @param string $isbn
     *
     * @return Product
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Get isbn
     *
     * @return string
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * Set imageUrl
     *
     * @param string $imageUrl
     *
     * @return Product
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get imageUrl
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Product
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @return string|UploadedFile
     */
    public function getImage()
    {
        if ($this->image instanceof UploadedFile) {
            return $this->image;
        }
        return $this->imageUrl;
    }

    /**
     * @param UploadedFile|null $image
     * @return Product
     */
    public function setImage(?UploadedFile $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        if ($this->getActive()) {
            return 'Active';
        }
        return 'Disabled';
    }

    /**
     * @return ArrayCollection|CollectionInterface
     */
    public function getReturns(): CollectionInterface
    {
        return $this->returns;
    }

    /**
     * @param ArrayCollection $returns
     * @return Product
     */
    public function setReturns(ArrayCollection $returns): Product
    {
        $this->returns = $returns;

        return $this;
    }

    /**
     * @return int
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @param int
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isEBook(): bool
    {
        return $this->type === self::E_BOOK;
    }

}

