<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
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
    #[Route('/add/newArticle',name:'app_addArticle',methods:['POST'])]
    public function addArticle(Request $request,CategoryRepository $catRepo,UserRepository $userRepo,EntityManagerInterface $em): Response
    {
        $article = new Article();
        $user = $userRepo->findOneBy(['id'=>$request->request->get('user')]);
        $category = $catRepo->findOneBy(['categoryName'=>$request->request->get('category')]);
        $article->setTitle($request->request->get('title'));
        $article->setDescription($request->request->get('description'));
        $article->setLikes(0);
        $article->setContent($request->request->get('content'));
        $article->setCategory($category);
        $article->setUser($user);
        $article->setPhotoPath('img-0'.rand(1,6).'.jpg');
        $article->setDatePub(new DateTime());
        $em->persist($article);
        $em->flush();
        return $this->render('article/add.html.twig');
    }

    #[Route('/add',name:'app_addArticle.page')]
    public function addArticlePage(): Response
    {
        return $this->render('article/add.html.twig');
    }

    #[Route('/addComment',name:'app_addComent',methods:['POST'])]
    public function addArticleComment(Request $request,ArticleRepository $reg,EntityManagerInterface $em): Response
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
