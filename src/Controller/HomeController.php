<?php

namespace App\Controller;

use App\Repository\CollectionRepository;
use App\Repository\SettingRepository;
use App\Repository\SlidersRepository;
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
        CollectionRepository $collectionRepos,
        Request $request,
        ): Response
    {
        //  On recupere la session via la requette
        $session = $request->getSession();
        $data = $settingRepo->findAll();
        // On récupére le Sliders
        $sliders = $slidersRepo->findAll();
        $collections = $collectionRepos->findAll();

        // dd($data);
        
        //  On lui passe un tableau avec la premier information qui il y'a a l'interieur
        $session->set("setting", $data[0]);


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            // un foit recupere on le passe la view 
            'sliders'  => $sliders,
            'collections'  => $collections
        ]);
    }
}
