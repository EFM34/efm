<?php

namespace App\Controller;

use App\Repository\PageRepository;
use App\Repository\SettingRepository;
use App\Repository\SlidersRepository;
use App\Repository\CollectionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        SettingRepository $settingRepo, 
        SlidersRepository $slidersRepo,
        CollectionRepository $collectionRepo,
        PageRepository $pageRepo,
        Request $request,
        ): Response
    {
        //  On recupere la session via la requette
        $session = $request->getSession();
        $data = $settingRepo->findAll();
        // On récupére le Sliders
        $sliders = $slidersRepo->findAll();
        $collections = $collectionRepo->findAll();
       

        // dd($data);
        
        //  On lui passe un tableau avec la premier information qui il y'a a l'interieur
        // Et on stocke les données  qui on a dans nous parametres su site
        $session->set("setting", $data[0]);

        // On declare les pages de header et footer 
        $headerPages = $pageRepo->findBy(['isHead' => true]);
        $footerPages = $pageRepo->findBy(['isFoot' => true]);
        // dd($headerPages);
        // Et on les stocke  dans la session
        $session->set("headerPages",  $headerPages);
        $session->set("footerPages",  $footerPages);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            // un foit recupere on le passe la view 
            'sliders'  => $sliders,
            'collections'  => $collections
        ]);
    }
}
