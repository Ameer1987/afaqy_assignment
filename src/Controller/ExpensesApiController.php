<?php

namespace App\Controller;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Vehicles;
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
        $vehicles = $em->getRepository(Vehicles::class)->findAll();

        $jsonContent = $serializer->serialize($vehicles, 'json');
        return $this->json($jsonContent);
    }
}
