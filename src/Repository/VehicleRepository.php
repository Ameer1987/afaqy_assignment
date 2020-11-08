<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Inflector\Inflector;

/**
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    // /**
    //  * @return Vehicle[] Returns an array of Vehicle objects
    //  */
    public function searchExpenses($vehicleName = '', $filters = [], $sort = [])
    {
        $qb = $this->createQueryBuilder('v');

        if ($vehicleName) {
            $qb->andWhere('v.name LIKE :name')
                ->setParameter('name', '%' . $vehicleName . '%');
        }

        // Do the necessary joins for the required expenses
        foreach (Vehicle::getExpenseTypes() as $key => $expenseType) {
            $getter = lcfirst(Inflector::pluralize((new \ReflectionClass($expenseType))->getShortName()));
            $qb->leftJoin('v.' . $getter, $key);

            $costField = $expenseType::getCostField();
            $creationDateField = $expenseType::getCreationDateField();

            // minimum cost filter 
            if (isset($filters['minimum_cost']) && $filters['minimum_cost'] > 0) {
                $qb->andWhere($key . '.' . $costField . ' >= :minimum_cost')
                    ->setParameter('minimum_cost', $filters['minimum_cost']);
            }

            // maximum cost filter 
            if (isset($filters['maximum_cost']) && $filters['maximum_cost'] > 0) {
                $qb->andWhere($key . '.' . $costField . ' <= :maximum_cost')
                    ->setParameter('maximum_cost', $filters['maximum_cost']);
            }

            // minimum creation date filter 
            if (isset($filters['minimum_creation_date'])) {
                $qb->andWhere($key . '.' . $creationDateField . ' >= :minimum_creation_date')
                    ->setParameter('minimum_creation_date', $filters['minimum_creation_date']);
            }

            // maximum creation date filter 
            if (isset($filters['maximum_creation_date'])) {
                $qb->andWhere($key . '.' . $creationDateField . ' <= :maximum_creation_date')
                    ->setParameter('maximum_creation_date', $filters['maximum_creation_date']);
            }
        }

        // get only the required expenses
        if (isset($filters['expense_types']) && count($filters['expense_types']) > 0) {
            foreach ($filters['expense_types'] as $expenseType) {
                if (Vehicle::getExpenseTypes()[$expenseType]) {
                    $qb->andWhere($expenseType . '.id IS NOT null');
                }
            }
        }
        // dd($qb->getQuery()->getSQL());


        // if ($sort) {
        //     $qb->orderBy('v.' . $sort['key'], $sort['value']);
        // }

        return $qb
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Vehicle
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
