<?php

namespace App\Controller ;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing \Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Article;

class ArticleController extends Controller{
 
    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function index()
    {
        $articles=$this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('articles/index.html.twig',["articles"=> $articles]);
    }
}