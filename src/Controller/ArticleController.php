<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article', name: 'article')]
class ArticleController extends AbstractController
{
    #[Route('/{id<\d+>}', name: 'app_article')]
    public function index($id): Response
    {
        return $this->render('article/index.html.twig',['id'=>$id]);
    }

    #[Route('/addArticle',name:'app_addArticle')]
    public function addArticlePage(): Response
    {
        return $this->render('article/add.html.twig');
    }
}
