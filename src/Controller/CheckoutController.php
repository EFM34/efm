<?php

namespace App\Controller;

use App\Services\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CheckoutController extends AbstractController
{
    // On injecte le service Cart
    public function __construct(private CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    #[Route('/checkout', name: 'app_checkout')]
    public function index(): Response
    {
         //  On récupére les détails du panier 
         $cart = $this->cartService->getCartDetails();

        // Si il y' rien dans notre panier 
        if(!count($cart["items"])) {
            return $this->redirectToRoute('app_home');
         }
         // On encode le panier en json ici et on le passe la view
        // $cart_json = json_encode($cart);


        return $this->render('checkout/index.html.twig', [
            'controller_name' => 'CheckoutController',
            'cart' => $cart
        ]);
    }
}
