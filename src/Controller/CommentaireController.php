<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireController extends AbstractController
{
    #[Route('/commentaire_update_{id<\d+>}', name: 'app_commentaire_update')]
    public function update(ManagerRegistry $doctrine, $id, Request $request): Response
    {
        $commentaire = $doctrine->getRepository(Commentaire::class)->find($id);

        $article = $commentaire->getArticle();

        $form = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager = $doctrine->getManager();
            $manager->persist($commentaire);
            $manager->flush();

            return $this->redirectToRoute('app_article', ['id' => $article->getId() ]);
        }

        return $this->render('article/unArticle.html.twig', [
            'formCommentaire' => $form->createView(),
            'article' => $article,
            'commentaires' => $article->getCommentaires()
        ]);
    }

    #[Route('/commentaire_update_{id<\d+>}', name: 'app_commentaire_update')]
    public function delete(CommentaireRepository $repo, $id){
        $commentaire = $repo->find($id);
        $article = $commentaire->getArticle();

        $repo->remove($commentaire, 1);

        return $this->redirectToRoute('app_article', ['id' => $article->getId()]);
    }

}
