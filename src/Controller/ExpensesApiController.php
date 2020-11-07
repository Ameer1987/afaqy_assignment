<?php

namespace App\Controller;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Vehicle;
use Doctrine\ORM\EntityManager;

/**
 * Cart Controller
 * @Route("/api/expenses", name="api_expenses_")
 * 
 */
class ExpensesApiController extends AbstractController
{
    /**
     * @Route("/list", name="list", methods={"GET"})
     */
    public function list(Request $request, SerializerInterface $serializer): Response
    {
        /** @var EntityManager $em */
        $em = $this->get('doctrine')->getManager();
        $vehicles = $em->getRepository(Vehicle::class)->findBy([], [], 10);

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

        $jsonContent = $serializer->serialize($expensesArray, 'json');
        return $this->json($jsonContent);
    }
}
