<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    // la route de cette fonction se trouve dans config/route.yaml
    public function index(ManagerRegistry $doctrine): Response
    {
        // on récupère le dernier article de la BDD (obsolète sur symfony 6)
        // $dernierArticle = $this->getDoctrine()->getRepository(Article::class)->findOneBy([], ["dateDeCreation" => "DESC"]);
        $dernierArticle = $doctrine->getRepository(Article::class)->findOneBy([], ["dateDeCreation" => "DESC"]);

        // on vérifie le contenu de notre
        // dd($dernierArticle);
        // on récupère le dernier article de la BDD

        return $this->render('home/index.html.twig', [ 'article' => $dernierArticle ]);
    }
}
