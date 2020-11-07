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

    public function serialize($vehicles, $type)
    {
        $expensesArray = [];
        foreach ($vehicles as $vehicle) {
            foreach ($vehicle->getExpenses() as $expense) {
                $expensesArray[] = [
                    'id' => $vehicle->getId(),
                    'name' => $vehicle->getName(),
                    'plateNumber' => $vehicle->getPlateNumber(),
                    'type' => $expense->getExpenseType(),
                    'cost' => $expense->getExpenseCost(),
                    'createdAt' => $expense->getExpenseCreationDate(),
                ];
            }
        }

        return $this->serializer->serialize($expensesArray, $type);;
    }
}
