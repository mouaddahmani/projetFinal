<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $articels = Array();
        $category=Array();
        $userRepo= $manager->getRepository(User::class);
        $users = $userRepo->findAll();
        $catg =['Visual Designs','Travel Events','Web Development','Video and Audio','Etiam auctor ac arcu'];
        $faker =  Factory::create();
        for ($i=0; $i<5 ;$i++) { 
            $category[$i] = new Category();
            $category[$i]->setCategoryName($catg[$i]);
            
            $manager->persist($category[$i]);
        }
        
        for ($i=0; $i < 25; $i++) { 
            $articels[$i] = new Article();
            $articels[$i]->setTitle($faker->sentence($nbWords = 6, $variableNbWords = true));
            $articels[$i]->setDescription($faker->sentence($nbWords = 50, $variableNbWords = true));
            $articels[$i]->setContent($faker->sentence($nbWords = 150, $variableNbWords = true));
            $articels[$i]->setLikes($faker->numberBetween($min = 0, $max = 150));
            $articels[$i]->setDatePub($faker->dateTime());
            $articels[$i]->setCategory($category[rand(0,4)]);
            $articels[$i]->setPhotoPath('img-0'.rand(1,6).'.jpg');
            $articels[$i]->setUser($users[rand(0,5)]);
            for ($j=0; $j < 3; $j++) { 
                $comment = new Comment();
                $comment->setContent($faker->sentence($nbWords = 150, $variableNbWords = true));
                $comment->setDateCom($faker->dateTime());
                $comment->setArticle($articels[$i]);
                $manager->persist($comment);
            }
            $manager->persist($articels[$i]);
        }

        $manager->flush();
    }
}
