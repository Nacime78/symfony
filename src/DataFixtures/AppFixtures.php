<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Auteur;
use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $article = new Article();
            $article
            ->setTitre("titre $i")
            ->setContenu('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin tincidunt velit vel erat rhoncus ornare. Aenean luctus magna malesuada aliquam lacinia. Nulla rutrum nunc in dolor venenatis, quis malesuada nisl pellentesque. Suspendisse potenti. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nullam vulputate euismod mauris, eget placerat diam ornare eget. Mauris lacinia sollicitudin massa sed cursus. Nunc viverra turpis et urna tincidunt, ac auctor ex semper. Vestibulum volutpat leo non justo malesuada, quis vulputate leo hendrerit.')
            ->setDateDeCreation(new DateTime('now'));
            $manager->persist($article);

            $auteur = new Auteur();
            $auteur
                ->setNom("lorem $i")
                ->setPrenom("ipsum $i")
                ->setDateDeNaissance(new DateTime('now'))
                ->setBiographie('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin tincidunt velit vel erat rhoncus ornare. Aenean luctus magna malesuada aliquam lacinia. Nulla rutrum nunc in dolor venenatis, quis malesuada nisl pellentesque. Suspendisse potenti. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nullam vulputate euismod mauris, eget placerat diam ornare eget. Mauris lacinia sollicitudin massa sed cursus. Nunc viverra turpis et urna tincidunt, ac auctor ex semper. Vestibulum volutpat leo non justo malesuada, quis vulputate leo hendrerit.');
                $manager->persist($auteur);
        }

        $manager->flush();
    }
}
