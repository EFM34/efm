<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Review;
use App\Entity\Product;
use App\Form\ReviewType;
use App\Repository\PageRepository;
use App\Repository\ReviewRepository;
use App\Repository\ProductRepository;
use App\Repository\SettingRepository;
use App\Repository\SlidersRepository;
use App\Repository\CategoryRepository;
use App\Repository\CollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{

    private $repoProduct;
    private $repoReview;
    private Security $security;

    public function __construct(
        ProductRepository $repoProduct, 
        Security $security
    )
    {
        $this->repoProduct = $repoProduct;
        $this->security = $security;
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

       
        $data = $settingRepo->findAll();
        $sliders = $slidersRepo->findAll();
        $collections = $collectionRepo->findBy(['isMega' => false]);
        $megaCollections = $collectionRepo->findBy(['isMega' => true]);
        $categories = $categoryRepo->findBy(['isMega' => true]);
        

        // $product->setImageUrls(json_decode($product->getImageUrls(), true));

        // dd($data);
        
        //  On lui passe un tableau avec la premier information qui il y'a a l'interieur
        // Et on stocke les données  qui on a dans nous parametres su site
        //  On recupere la session via la requette
        $session = $request->getSession();
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

    #[Route('/product/get/{id}', name: 'app_product_by_id')]
    public function getProductById(int $id) 
    {
        // On recupere le produit via son slug
        $product = $this->repoProduct->findOneBy(['id' => $id]);
        
        // Vérifie si le produit existe
        if(!$product) {
            // returne  de json 
            return $this->json(false);
        }
        

        // Si panier exist on returne les infos du produit en format json
        return $this->json([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'imageUrls' => $product->getImageUrls(),
            'soldePrice' => $product->getSoldePrice(),
            'regularPrice' => $product->getRegularPrice(),
        ]);
    }
    
    
    #[Route('/product/{slug}', name: 'app_product_by_slug')]
    public function showProduct(string $slug) 
    {
        // On recupere le produit via son slug
        $product = $this->repoProduct->findOneBy(['slug' => $slug]);
        // dd($product->getImageUrls());
        
        // Vérifie si le produit existe
        if(!$product) {
            // redirection ver  la page d'erreur
            return $this->redirectToRoute('app_error');
        }
        
        // return $this->redirectToRoute('app_product_by_slug', ['slug' => $slug]);

        // Si on trouve le produit on redirige ver la page d'affichage 
        // Envoi des données au template
        return $this->render('product/show_product_by_slug.html.twig', [
            'product' => $product
        ]);
    }


    #[Route('/error', name: 'app_error')]
    public function errorPage() 
    {
        // Si on retrouve pas la page on returne une page d'erreur  404 que aon a créer 
        return $this->render('page/not-found.html.twig', [
            'controller_name' => 'PageController'
        ]);
    }

}
