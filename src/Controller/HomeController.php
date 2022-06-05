<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepo,Request $req): Response
    {
        $page = $req->query->get('page');
        $nbr = $req->query->get('nbr');
        
        if(!$req->query->has('page')){
            $page = 1;
        }
        if(!$req->query->has('nbr')){
            $nbr = 6;
        }
        
        
        $articles = $articleRepo->findBy([],[],$nbr,($page-1) *$nbr);
        $nbrArticle = $articleRepo->count([]);
        $totalpage = ceil($nbrArticle /$nbr);
        $debut = $page - 2;
        $fin = $page + 2;
        if ($debut < 3) {
            $debut = 1;
            $fin = 5;
        }
        if ($page == $totalpage) {
            $debut = $page - 4 <= 0 ? 1 : $page - 4;
            $fin = $page;
        }
        if ($fin > $totalpage) {
            $fin = $totalpage;
        }
        return $this->render('home/index.html.twig', [
            'articles' => $articles,
            'debut'=>$debut,
            'fin'=>$fin,
            'page'=>$page
        ]);
    }
}
