<?php

namespace App\Controller;

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
        Request $request,
        SlidersRepository $slidersRepo
        ): Response
    {
        //  On recupere la session via la requette
        $session = $request->getSession();
        $data = $settingRepo->findAll();
        // dd($data);
        
        //  On lui passe un tableau avec la premier information qui il y'a a l'interieur
        $session->set("setting", $data[0]);

        // On récupére le Sliders
        $sliders = $slidersRepo->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            // un foit recupere on le passe la view 
            'sliders'  => $sliders
        ]);
    }
}
