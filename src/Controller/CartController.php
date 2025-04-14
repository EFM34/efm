<?php

namespace App\Controller;


use App\Services\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\session;

final class CartController extends AbstractController
{
    public function __construct(private CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    
    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        //  On récupére les détails du panier 
        $cart = $this->cartService->getCartDetails();

        // On encode le panier en json ici et on le passe la view
        $cart_json = json_encode($cart);

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cart' => $cart,
            'cart_json' => $cart_json
        ]);
    }


    #[Route('/cart/add/{productId}/{count}', name: 'app_add_to_cart')]
    public function addToCart(string $productId, int $count = 1): Response
    {
        // On ajoute au panier
       $this->cartService->addToCart($productId, $count);
        //  On récupére les détails du panier 
        $cart = $this->cartService->getCartDetails();
        

        // On return du json 
        return $this->json($cart);
    }


    #[Route('/cart/remove/{productId}/{count}', name: 'app_remove_to_cart')]
    public function removeToCart(string $productId, int $count = 1): Response
    {
        // On supprime le produit du panier
        $this->cartService->removeToCart($productId, $count);
        
        //  On récupére les détails du panier 
        $cart = $this->cartService->getCartDetails();
        
        // Et on redirige ver le page panier en PHP
        // return $this->redirectToRoute("app_cart");

        // On return du json 
        return $this->json($cart);
        
    }



    
    #[Route('/cart/get', name: 'app_get_cart')]
    public function getCart(): Response
    {
        //  On récupére les détails du panier 
        $cart = $this->cartService->getCartDetails();
        
         // On return du json 
         return $this->json($cart);
    }

    
        // Et on redirige ver le page panier en PHP 
        // return $this->redirectToRoute("app_cart");
}
