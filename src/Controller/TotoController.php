<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TotoController extends AbstractController
{
    #[Route('/toto', name: 'app_toto')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $article3 = $doctrine->getRepository(Article::class)->findBy([], ["id" => "DESC"], 3, $offset = null);

        return $this->render('toto/toto.html.twig', [
            'article3' => $article3
        ]);
    }
}
