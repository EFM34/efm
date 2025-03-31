<?php

namespace App\Controller;

use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends AbstractController
{
    #[Route('/page/{slug}', name: 'app_page')]
    public function index(string $slug, PageRepository $pageRepo): Response
    {
        // On récupére une page si ça existe 
        $page = $pageRepo->findOneBy(["slug" => $slug]);

        // Si non 
        if(!$page){
            //  Redirect to error pages dedier 
        }
        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
            // on passe notre page a la view twig 
            'page'  =>  $page
        ]);
    }
}
