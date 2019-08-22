<?php


namespace AppBundle\Repository;


use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;

class ActivityRepository implements ActivityRepositoryInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Returns clients joined with union of orders, reports and returns.
     *
     * @return array
     * @throws DBALException
     */
    public function getLogData(): array
    {
        $db = $this->entityManager->getConnection();
        $sql = '
            SELECT 
                a.id AS id,
                a.activity AS activity,
                IF(c.private_person = 1, c.responsible_person, c.company) AS client,
                a.quantity AS products,
                a.date_added AS dateAdded
            FROM clients AS c
            JOIN 
            (SELECT 
                o.id, 
                o.client_id, 
                o.date_added,
                SUM(d.quantity) AS quantity,
                \'Order\' AS activity
            FROM orders AS o
            LEFT JOIN order_details AS d 
            ON o.id = d.order_id
            GROUP BY o.id
            
            UNION
            
            SELECT 
                r.id, 
                r.client_id, 
                r.date_added,
                SUM(d.quantity) AS qunatity,
                \'Report\' AS activity
            FROM reports AS r
            LEFT JOIN report_details AS d 
            ON r.id = d.report_id
            GROUP BY r.id
            
            UNION
            
            SELECT 
                r.id, 
                r.client_id, 
                r.date_added, 
                r.quantity,
                \'Return\' AS activity
            FROM returns AS r) AS a 
            ON a.client_id = c.id
            
            ORDER BY a.date_added DESC
        ';

        $stmt = $db->query($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}