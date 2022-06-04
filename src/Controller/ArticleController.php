<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article', name: 'article')]
class ArticleController extends AbstractController
{
    #[Route('/{id<\d+>}', name: 'app_article')]
    public function index($id,ArticleRepository $articleRepo): Response
    {
        $article = $articleRepo->findOneBy(['id'=>$id]);
        return $this->render('article/index.html.twig',['article'=>$article]);
    }

    #[Route('/add',name:'app_addArticle.page')]
    public function addArticlePage(): Response
    {
        return $this->render('article/add.html.twig');
    }

    #[Route('/addComment',name:'app_addComent',methods:['POST'])]
    public function addArticle(Request $request,ArticleRepository $reg,EntityManagerInterface $em): Response
    {
       
        $comment = new Comment();
        $article = $reg->findOneBy(['id'=>$request->request->get('id')]);
        $comment->setContent( $request->request->get('content'));
        $comment->setDateCom(new DateTime());
        $comment->setArticle($article);
        $em->persist($comment);
        $em->flush();
        return $this->forward('App\Controller\ArticleController::index',['id'=>$request->request->get('id')]);
    }
}
