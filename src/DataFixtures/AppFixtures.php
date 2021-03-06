<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Créer notre faker pour générer de belles données aléatoires
        $faker = \Faker\Factory::create("fr_FR");

        //Gérer les catégories
        $categories =[
            "Travel&Adventure",
            "Sport",
            "Entertainment",
            "Human Relations",
            "Others"
        ];
        $cat=[];
        foreach ($categories as $category){
            $entity = new Category();
            $entity->setName($category);
            $cat[]=$entity;
            $manager->persist($entity);
        }
        $manager->flush();

        for ($i = 0; $i<200; $i++)  {
            //Créer un wish vide
            $wish = new Wish();
            //Hydrate le wish
            $wish->setTitle($faker->sentence());
            $wish->setDescription($faker->realText());
            $wish->setAuthor($faker->userName);
            $wish->setIsPublished($faker->boolean(90));
            $wish->setDateCreated($faker->dateTimeBetween('-2 years'));
            $wish->setLikes($faker->numberBetween(0,5000));
            $wish->setCategory($faker->randomElement($cat));
            //Demander à Doctrine de sauvegarder ce wish
            $manager->persist($wish);
        }
        //Exécuter la requête sql
        $manager->flush();

    }
}
