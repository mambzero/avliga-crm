<?php


namespace AppBundle\Repository;


use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;

class WarehouseRepository implements WarehouseRepositoryInterface
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     * @throws DBALException
     */
    public function getStocks(): array
    {
        $db = $this->entityManager->getConnection();
        $sql = '
            SELECT 
                p.title,
                e.quantity as entries,
                d.quantity as orders,
                e.quantity - d.quantity as stocks
            FROM products AS p 
   
            JOIN (
            SELECT 
                p.id AS product_id, 
                IF(SUM(d.quantity) IS NULL, 0, SUM(d.quantity)) AS quantity 
            FROM products AS p
            LEFT JOIN order_details AS d ON p.id = d.product_id
            GROUP BY p.id
            ) AS d ON p.id = d.product_id
            
            JOIN (
            SELECT 
                p.id AS product_id, 
                IF(SUM(e.quantity) IS NULL, 0, SUM(e.quantity)) AS quantity 
            FROM products AS p
            LEFT JOIN entries AS e ON p.id = e.product_id
            GROUP BY p.id
            ) AS e ON p.id = e.product_id
            WHERE p.active = 1
        ';

        $stmt = $db->query($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param int $id
     * @return int
     * @throws DBALException
     */
    public function getStock(int $id): int
    {
        $db = $this->entityManager->getConnection();
        $sql = '
            SELECT 
                e.quantity - d.quantity as stock
            FROM products AS p 
   
            JOIN (
            SELECT 
                p.id AS product_id, 
                IF(SUM(d.quantity) IS NULL, 0, SUM(d.quantity)) AS quantity 
            FROM products AS p
            LEFT JOIN order_details AS d ON p.id = d.product_id
            GROUP BY p.id
            ) AS d ON p.id = d.product_id
            
            JOIN (
            SELECT 
                p.id AS product_id, 
                IF(SUM(e.quantity) IS NULL, 0, SUM(e.quantity)) AS quantity 
            FROM products AS p
            LEFT JOIN entries AS e ON p.id = e.product_id
            GROUP BY p.id
            ) AS e ON p.id = e.product_id
            WHERE p.id = :id
        ';

        $stmt = $db->prepare($sql);
        $stmt->execute(['id'=>$id]);

        $result = $stmt->fetch();

        if (!$result) {
            return 0;
        }

        return $result['stock'];
    }
}