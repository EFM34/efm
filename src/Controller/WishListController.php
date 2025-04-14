<?php

namespace App\Controller;

use App\Services\WishListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WishListController extends AbstractController
{
    
    public function __construct(
        private WishListService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }


    #[Route('/wishlist', name: 'app_wishlist')]
    public function index(): Response
    {
        // On récupére les détails de la WishList
        $wishlist = $this->wishlistService->getWishListDetails();

        
        // On  stocke dans la variable
        // Et on passe a notre template wishlist/index.html.twig dans la data-wishlist
        // <div class="main_content wishlist_content" data-wishlist="{{wishlist_json}}">
        $wishlist_json = json_encode($wishlist);

        return $this->render('wishlist/index.html.twig', [
            'controller_name' => 'WishListController',
            'wishlist' => $wishlist,
            'wishlist_json' => $wishlist_json,
        ]);
    }


    #[Route('/wishlist/add/{productId}', name: 'app_add_to_wishlist')]
    public function addToWishList(string $productId): Response
    {
        // On ajoute au wishlist
       $this->wishlistService->addToWishList($productId);
        // On récupére les détails 
        $wishlist = $this->wishlistService->getWishListDetails();
        
        // Redirection en PHP vers la page WishList
        // return $this->redirectToRoute('app_wishlist');

        // On return du json 
        return $this->json($wishlist);
    }


    #[Route('/wishlist/remove/{productId}', name: 'app_remove_to_wishlist')]
    public function removeToWishList(string $productId): Response
    {
        // On supprime le produit du wishlist
        $this->wishlistService->removeToWishList($productId);
        //  Et on récupére les détails
        $wishlist = $this->wishlistService->getWishListDetails();
        
        // Redirection en PHP  ers la page WishList
        // return $this->redirectToRoute('app_wishlist');

        // On returne en JS / On return du json 
        return $this->json($wishlist);
    }



    
    #[Route('/wishlist/get', name: 'app_get_wishlist')]
    public function getWishList(): Response
    {
        //  On récupére les détails
        $wishlist = $this->wishlistService->getWishListDetails();
        
        // Redirection en PHP vers la page WishList
        return $this->redirectToRoute('app_wishlist');

        // On returne en JS / On return du json 
        // return $this->json($wishlist);
    }
}
