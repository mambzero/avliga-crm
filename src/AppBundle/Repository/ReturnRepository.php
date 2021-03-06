<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ReEntry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\NonUniqueResultException;
use Exception;

/**
 * ReturnsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReturnRepository extends EntityRepository implements ReturnRepositoryInterface
{
    /**
     * ReturnRepository constructor.
     * @param EntityManagerInterface|EntityManager $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, new Mapping\ClassMetadata(ReEntry::class));
    }

    /**
     * @param ReEntry $reEntry
     * @return bool
     */
    public function insert(ReEntry $reEntry): bool
    {
        try {
            $this->_em->persist($reEntry);
            $this->_em->flush();
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param ReEntry $reEntry
     * @return bool
     */
    public function update(ReEntry $reEntry): bool
    {
        try {
            $this->_em->merge($reEntry);
            $this->_em->flush();
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param ReEntry $reEntry
     * @return bool
     */
    public function delete(ReEntry $reEntry): bool
    {
        try {
            $this->_em->remove($reEntry);
            $this->_em->flush();
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @return ReEntry[]
     */
    public function listAll(): array
    {
        return $this->createQueryBuilder('r')
            ->select([
                'r.id',
                'CASE WHEN c.privatePerson = 1 THEN c.responsiblePerson ELSE c.company END as client',
                'p.title as product',
                'r.quantity',
                'r.dateAdded'])
            ->join('r.client', 'c')
            ->join('r.product', 'p')
            ->orderBy('r.dateAdded', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $id
     * @return ReEntry|Object|null
     */
    public function findOne(int $id): ?ReEntry
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * @param int $id
     * @return int
     * @throws NonUniqueResultException
     */
    public function getQuantity(int $id): int
    {
        $report = $this->createQueryBuilder('r')
            ->select('r.quantity as quantity')
            ->where('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        return $report['quantity'];
    }

    /**
     * @return int|null
     * @throws NonUniqueResultException
     */
    public function getReturnedProductsCount(): int
    {
        $result = $this->createQueryBuilder('r')
            ->select('SUM(r.quantity) as quantity')
            ->join('r.product', 'p')
            ->getQuery()
            ->getOneOrNullResult();

        return $result['quantity'] === null ? 0 : $result['quantity'];
    }
}
