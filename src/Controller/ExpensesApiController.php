<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Vehicle;
use App\Serializer\ExpensesSerializer;
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
    public function list(Request $request, ExpensesSerializer $expensesSerializer): Response
    {
        $searchKeyword = $request->get('vehicle_name');
        $filters = $request->get('filters');
        $page = $request->get('page', 1);

        /** @var EntityManager $em */
        $em = $this->get('doctrine')->getManager();
        $vehicles = $em->getRepository(Vehicle::class)->searchExpenses($searchKeyword, $filters);

        $jsonContent = $expensesSerializer->serialize($vehicles, 'json', $page);
        return $this->json($jsonContent);
    }
}
