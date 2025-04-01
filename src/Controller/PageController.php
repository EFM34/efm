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
            // Si on retrouve pas la page on returne une page d'erreur  404 que aon a créer 
            return $this->render('page/not-found.html.twig', [
                'controller_name' => 'PageController'
            ]);
        }
        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
            // on passe notre page a la view twig 
            'page'  =>  $page
        ]);
    }

}
