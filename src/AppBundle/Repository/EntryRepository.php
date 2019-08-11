<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Entry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Exception;

/**
 * EntryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EntryRepository extends EntityRepository implements EntryRepositoryInterface
{
    /**
     * EntryRepository constructor.
     * @param EntityManagerInterface|EntityManager $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, new Mapping\ClassMetadata(Entry::class));
    }

    /**
     * @param Entry $entry
     * @return bool
     */
    public function insert(Entry $entry): bool
    {
        try {
            $this->_em->persist($entry);
            $this->_em->flush();
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param Entry $entry
     * @return bool
     */
    public function update(Entry $entry): bool
    {
        try {
            $this->_em->merge($entry);
            $this->_em->flush();
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @return Entry[]
     */
    public function listAll(): array
    {
        return $this->createQueryBuilder('e')
            ->select('e.id, p.title as product, e.quantity, e.dateAdded')
            ->join('e.product', 'p')
            ->orderBy('e.dateAdded', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Entry $entry
     * @return bool
     */
    public function delete(Entry $entry): bool
    {
        try {
            $this->_em->remove($entry);
            $this->_em->flush();
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param int $id
     * @return Entry|Object|null
     */
    public function findOne(int $id): ?Entry
    {
        return $this->findOneBy(['id' => $id]);
    }
}
