<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Domaine;
use App\Entity\Marque;
use App\Entity\Pays;
use App\Entity\Photo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /*
        $faker = \Faker\Factory::create('fr_FR');

        // liste des catégories
        $categories = array('HOMMES', 'FEMMES', 'ENFANTS');
        $countCategories = count($categories);

        // liste des domaines
        $doamines = array('CHAUSSURES', 'VETÊMENTS', 'ACCESSOIRES');
        $coutDomainnes = count($doamines);

        // liste des marques
        $marques = array('NIKE', 'ADIDAS', 'ETAM', 'LACOSTE', 'JULES', 'ORCHESTRA');
        $coutMarques = count($marques);

        foreach ($marques as $uneMarque) {
            $marque = new Marque();
            $marque->setTitre($uneMarque)
                ->setDescription($faker->paragraph);
            $manager->persist($marque);
        }



        foreach ($categories as $category) {
            $categorie = new Categorie();
            $categorie->setTitre($category)
                ->setDescription($faker->paragraph);
            $manager->persist($categorie);

            foreach ($doamines as $doamine) {
                $famille = new Domaine();
                $famille->setTitre($doamine)
                    ->setDescription($faker->paragraph);
                $manager->persist($famille);
            }


            // pour chaque catégorie on va créer entre 4 et 6 articles
            for ($j = 1; $j <= mt_rand(50, 60); $j++) {
                $contenue = '<p>';
                $contenue .= join($faker->paragraphs(3), '</p><p>');
                $contenue .= '</p>';

                $article = new Article();
                $article->setTitre($faker->sentence())
                    ->setDescription($contenue)
                    ->setMotRecherche($faker->sentence())
                    ->setPrix($faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = NULL))
                    ->setRemise($faker->numberBetween($min = 0, $max = 90))
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setCategorie($categorie)
                    ->setDomaine($famille)
                    ->setMarque($marque);
                $manager->persist($article);
            }
        }

        */
        /*
        // Pays ('033', 'France'),	('034', 'Espagne'),	('035', 'Angleterre'),	('039', 'Italie');
        // $tPays = array('France', 'Espagne', 'Angleterre');
        $tPays = array(
            "033" => "France",
            "034" => "Espagne",
            "035" => "Angleterre"
        );

        foreach ($tPays as $cle => $valeur) {
            $UnPays = new Pays();
            $UnPays->setNom($valeur)->setCode($cle)
            ;
            $manager->persist($UnPays);
        }
        */
        $manager->flush();
    }
}
