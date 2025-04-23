<?php

namespace App\Controller;


use App\Entity\Carrier;
use App\Repository\CarrierRepository;
use App\Services\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\session;
use Symfony\Component\HttpFoundation\Request;

final class CartController extends AbstractController
{
    public function __construct(
        private CartService $cartService, 
        private CarrierRepository $carrierRepo,
    ) {
        $this->cartService = $cartService;
    }

    
    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        //  On récupére les détails du panier 
        $cart = $this->cartService->getCartDetails();

        //  On récupere tout les tansporteurs
        $carriers = $this->carrierRepo->findAll();

        //1)  On boucle sur le tableu des transporteurs
        foreach ($carriers as $key => $carrier) {
            $carriers[$key] = [
                // On indique les valeurs que on veut stocker
                "id" => $carrier->getId(),
                "name" => $carrier->getName(),
                "description" => $carrier->getDescription(),
                "price" => $carrier->getPrice(),
            ];
        }

        // On encode le panier en json ici et on le passe la view
        $cart_json = json_encode($cart);

        // 2) On encode les transporteur en json ici 
        $carriers_json = json_encode($carriers);

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cart' => $cart,
            'carriers' =>  $carriers,
            'cart_json' => $cart_json,
            // 3) Et on le passe la view
            'carriers_json' => $carriers_json
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

     
    #[Route('/cart/carrier', name: 'app_update_cart_carrier', methods:["POST"])]
    public function updateCartCarrier(Request $req): Response
    {

        $id = $req->getPayload()->get("carrierId");
        
        //  On récupére le transporteur par son  id
        $carrier = $this->carrierRepo->findOneById($id);
        
        // Si on rétrouve pas le transporteur 
        if(!$carrier){
            // On va rediriger ver la page d'accueil
            return $this->redirectToRoute("app_home");
        }
        // Dans le cas ou on le retrouve on doit le metre a jour
        $this->cartService->update("carrier", [
            // On indique les valeurs que on veut stocker
            "id" => $carrier->getId(),
            "name" => $carrier->getName(),
            "description" => $carrier->getDescription(),
            "price" => $carrier->getPrice(),
        ]);

        // On rediriger ver le panier
        return $this->redirectToRoute("app_cart");
        
    }

    
        // Et on redirige ver le page panier en PHP 
        // return $this->redirectToRoute("app_cart");
}
