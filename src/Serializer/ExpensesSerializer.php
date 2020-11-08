<?php

namespace App\Serializer;

use JMS\Serializer\SerializerInterface;

/**
 * Expenses Serializer
 * 
 */
class ExpensesSerializer
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize($vehicles, $type, $page = 1)
    {
        $expensesArray = [];
        foreach ($vehicles as $vehicle) {
            foreach ($vehicle->getExpenses() as $expense) {
                $costGetter = 'get' . ucfirst($expense::getCostField());
                $creationDateGetter = 'get' . ucfirst($expense::getCreationDateField());

                $expensesArray[] = [
                    'id' => $vehicle->getId(),
                    'name' => $vehicle->getName(),
                    'plateNumber' => $vehicle->getPlateNumber(),
                    'type' => $expense->getExpenseType(),
                    'cost' => $expense->$costGetter(),
                    'createdAt' => $expense->$creationDateGetter(),
                ];
            }
        }

        $expensesArray = array_slice($expensesArray, ($page - 1), 10);
        // dd($expensesArray);

        return $this->serializer->serialize($expensesArray, $type);;
    }
}
