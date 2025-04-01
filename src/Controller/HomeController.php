<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PageRepository;
use App\Repository\SettingRepository;
use App\Repository\SlidersRepository;
use App\Repository\CollectionRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{

    private $repoProduct;

    public function __construct(ProductRepository $repoProduct)
    {
        $this->repoProduct = $repoProduct;
    }



    #[Route('/', name: 'app_home')]
    public function index(
        SettingRepository $settingRepo, 
        SlidersRepository $slidersRepo,
        CollectionRepository $collectionRepo,
        CategoryRepository $categoryRepo,
        PageRepository $pageRepo,
        Request $request,
        ): Response
    {
        //  On recupere la session via la requette
        $session = $request->getSession();
        $data = $settingRepo->findAll();
        $sliders = $slidersRepo->findAll();
        $collections = $collectionRepo->findBy(['isMega' => false]);
        $megaCollections = $collectionRepo->findBy(['isMega' => true]);
        $categories = $categoryRepo->findBy(['isMega' => true]);
       

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
        $session->set("categories",   $categories);
        $session->set("megaCollections", $megaCollections);



        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            // un foit recupere on le passe la view 
            'sliders'  => $sliders,
            'collections'  => $collections,
            // On récupére toute les produits et on le passe a la view
            'productsBestSeller' =>  $this->repoProduct->findBy(['isBestSeller' => true]),
            'productsNewArrival' =>  $this->repoProduct->findBy(['isNewArrival' => true]),
            'productsFeatured' =>  $this->repoProduct->findBy(['isFeatured' => true]),
            'productsSpecialOffer' =>  $this->repoProduct->findBy(['isSpecialOffer' => true]),

        ]);
    }
}
