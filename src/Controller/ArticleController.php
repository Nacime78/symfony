<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ArticleController extends AbstractController
{

    #[Route('/articles', name: 'app_articles')]
    public function articles(ManagerRegistry $doctrine): Response
    {
        // on récupère les articles
        $allArticle = $doctrine->getRepository(Article::class)->findBy([], ["id" => "DESC"]);

        // on vérifie si on a bien récupérer les articles
        return $this->render('article/articles.html.twig', [ 'allArticle' => $allArticle
        ]);
    }


    #[Route('/article_{id<\d+>}', name: 'app_article')]
    public function unArticle(ManagerRegistry $doctrine, $id, Request $request)
    {
        $commentaire = new Commentaire();

        $form = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);

        // on récupère l'article dont l'id est celui dans l'URL
        $article = $doctrine->getRepository(Article::class)->find($id);

        // vérifier qu'un formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            $commentaire->setArticle($article);
            // on récupère le manager de doctrine
            $manager = $doctrine->getManager();

            $manager->persist($commentaire);

            $manager->flush();

            return $this->redirectToRoute('app_article', [
                'id' => $id
            ]);
        }

        // on vérifie si je récupère bien un article
        // dd($unArticle);

        return $this->render('article/unArticle.html.twig', [
            'article' => $article,
            'id' => $id,
            'formCommentaire' => $form->createView(),
            'commentaires' => $article->getCommentaires()
        ]);
    }


    #[Route('/article_ajout', name: 'app_article_ajout')]
    public function ajout(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger)
    {
        // on crée un objet Article
        $article = new Article();

        // on lie ArticleType avec l'objet créé
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        // vérifier qu'un formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()){

            if($form->get('file')->getData()){
                // on récupère la donnée du champ file du formulaire
                $file = $form->get('file')->getData();
                // le slug permet de transformer une chaine de caractère ex : ('le mot clé' => 'le-mot-clé')
                // on modifie le nom de l'image en y mettant le titre sous forme de slug (sans espaces, accents...)
                // puis un id généré tout en gardant l'extension  de l'image
                $fileName = $slugger->slug($article->getTitre()) . uniqid() . '.' . $file->guessExtension();
    
                try{
                    $file->move($this->getParameter('article_image'), $fileName);
                }catch(FileException $e){
                    // générer les exceptions en cas d'erreur durant l'upload
                }
    
                // on affecte fileName à l'article pour l'enregistrer en bdd
                $article->setImage($fileName);
            }

            // on affecte la date car elle ne s'ajoute pas depuis le formulaire
            $article->setDateDeCreation(new DateTime("now"));
            // on récupère le manager de doctrine
            $manager = $doctrine->getManager();

            $manager->persist($article);

            $manager->flush();

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('article/formulaire.html.twig', [
            'formArticle' => $form->createView(),
        ]);
    }


    #[Route('/article_editer_{id<\d+>}', name: 'app_article_editer')]
    public function edit(Request $request, ManagerRegistry $doctrine, $id, SluggerInterface $slugger){
        $article = $doctrine->getRepository(Article::class)->find($id);

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        $article->setDateDeModification(new DateTime("now"));

            if ($form->get('file')->getData()) {

                $file = $form->get('file')->getData();

                $fileName = $slugger->slug($article->getTitre()) . uniqid() . '.' . $file->guessExtension();

                try {
                    $file->move($this->getParameter('article_image'), $fileName);
                } catch (FileException $e) {

                }

                $article->setImage($fileName);
            }
            // on récupère le manager de doctrine
            $manager = $doctrine->getManager();

            $manager->persist($article);

            $manager->flush();

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('article/formulaire.html.twig', [
            'formArticle' => $form->createView()
        ]);
    }

    #[Route('/article_delete_{id<\d+>}', name: 'app_article_delete')]
    public function delete(Request $request, ManagerRegistry $doctrine, $id)
    {
        $article = $doctrine->getRepository(Article::class)->find($id);

        $manager = $doctrine->getManager();

        $manager->remove($article);

        $manager->flush();

        return $this->redirectToRoute('app_articles');
    }

}
