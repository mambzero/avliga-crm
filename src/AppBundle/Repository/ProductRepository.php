<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Exception;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends EntityRepository implements ProductRepositoryInterface
{
    /**
     * ClientRepository constructor.
     * @param EntityManagerInterface|EntityManager $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, new Mapping\ClassMetadata(Product::class));
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function insert(Product $product)
    {
        try {
            $this->_em->persist($product);
            $this->_em->flush();
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function update(Product $product)
    {
        try {
            $this->_em->merge($product);
            $this->_em->flush();
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @return Product[]
     */
    public function listAll()
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.title, p.isbn, p.price')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $id
     * @return Product|Object|null
     */
    public function findOne($id)
    {
        return $this->find($id);
    }
}
