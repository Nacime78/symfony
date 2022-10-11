<?php

namespace App\Controller;

use DateTime;
use App\Entity\Auteur;
use App\Entity\Article;
use App\Form\AuteurType;
use App\Form\ArticleType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuteurController extends AbstractController
{
    #[Route('/auteurs', name: 'app_auteurs')]
    public function auteurs(ManagerRegistry $doctrine): Response
    {
        // on récupère les articles
        $auteurs = $doctrine->getRepository(Auteur::class)->findBy([], ["id" => "DESC"]);

        // on vérifie si on a bien récupérer les articles
        return $this->render('auteur/auteurs.html.twig', [
            'auteurs' => $auteurs
        ]);
    }


    #[Route('/auteur_{id<\d+>}', name: 'app_auteur')]
    public function unAuteur(ManagerRegistry $doctrine, $id)
    {
        // on récupère l'article dont l'id est celui dans l'URL
        $auteur = $doctrine->getRepository(Auteur::class)->find($id);

        // on vérifie si je récupère bien un article
        // dd($unArticle);

        return $this->render('auteur/auteur.html.twig', [
            'auteur' => $auteur,
            'id' => $id,
        ]);
    }

    #[Route('/auteur_ajout', name: 'app_auteur_ajout')]
    public function ajout(Request $request, ManagerRegistry $doctrine)
    {
        // on crée un objet Article
        $auteur = new Auteur();

        // on lie ArticleType avec l'objet créé
        $form = $this->createForm(AuteurType::class, $auteur);

        $form->handleRequest($request);

        // vérifier qu'un formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // on récupère le manager de doctrine
            $manager = $doctrine->getManager();

            $manager->persist($auteur);

            $manager->flush();

            return $this->redirectToRoute('app_auteurs');
        }

        return $this->render('auteur/formAuteur.html.twig', [
            'formAuteur' => $form->createView()
        ]);
    }

    #[Route('/auteur_editer_{id<\d+>}', name: 'app_auteur_editer')]
    public function edit(Request $request, ManagerRegistry $doctrine, $id)
    {
        $auteur = $doctrine->getRepository(Auteur::class)->find($id);

        $form = $this->createForm(AuteurType::class, $auteur);

        $form->handleRequest($request);

        // vérifier qu'un formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // on récupère le manager de doctrine
            $manager = $doctrine->getManager();

            $manager->persist($auteur);

            $manager->flush();

            return $this->redirectToRoute('app_auteurs');
        }

        return $this->render('auteur/formAuteur.html.twig', [
            'formAuteur' => $form->createView()
        ]);
    }

    #[Route('/auteur_delete_{id<\d+>}', name: 'app_auteur_delete')]
    public function delete(ManagerRegistry $doctrine, $id)
    {
        $auteur = $doctrine->getRepository(Auteur::class)->find($id);

        $manager = $doctrine->getManager();

        $manager->remove($auteur, 1);

        return $this->redirectToRoute('app_auteurs');
    }
}
