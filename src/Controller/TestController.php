<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        $personnes = [
            [
                'prenom' => 'Nacime',
                'nom' => 'Boubekeur'
            ],
            [
                'prenom' => 'Naruto',
                'nom' => 'Uzumaki'
            ],
            [
                'prenom' => 'Sasuke',
                'nom' => 'Uchiha'
            ],
        ];

        return $this->render('test.html.twig', ['personnes' => $personnes]);
    }
}
