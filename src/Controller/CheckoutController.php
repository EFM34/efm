<?php

namespace App\Controller;

use App\Services\CartService;
use App\Repository\AddressRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CheckoutController extends AbstractController
{
    // On injecte le service Cart
    public function __construct(
        private CartService $cartService,
        private RequestStack $requestStack
        )
    {
        $this->cartService = $cartService;
        $this->session = $requestStack->getSession();
    }

    #[Route('/checkout', name: 'app_checkout')]
    public function index(AddressRepository $addressRepository): Response
    {
        //  On récupére les détails du panier 
        $cart = $this->cartService->getCartDetails();

        // Si il y' rien dans notre panier 
        if(!count($cart["items"])) {
            return $this->redirectToRoute('app_home');
        }

        // On récupere l'utilisataeur connecter
        $user = $this->getUser();
        if(!$user) {
            // On stock dan la Session 
            $this->session->set("next", "app_checkout");
            return $this->redirectToRoute("app_login");
        }

        // On récupère toute les adrese de l'utilisateur
        $addresses = $addressRepository->findByUser($user);
        // On encode le panier en json ici et on le passe la view
        // $cart_json = json_encode($cart);


        return $this->render('checkout/index.html.twig', [
            'controller_name' => 'CheckoutController',
            'cart' => $cart,
            'addresses' => $addresses
        ]);
    }
}
