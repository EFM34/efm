<?php

namespace App\Controller\Api;

use App\Services\CartService;
use App\Repository\CarrierRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ApiCartController extends AbstractController
{
    #[Route('/api/cart/update/carrier/{id}', name: 'app_cart_update_carrier', methods: ['GET'])]
    public function index($id, 
        CartService $cartService,
        CarrierRepository $carrierRepo): Response
    {
        // 'app_api_api_cart'
        $carrier = $carrierRepo->findOneById($id);

        if(!$carrier){
            return $this->json([
                "isSuccess" => false,
                "message" => "Transporteur introuvable !"
            ]);
        }

        // On fait la mise à jour
        $cartService->update("carrier", [
            // On indique les valeurs que on veut stocker
            "id" => $carrier->getId(),
            "name" => $carrier->getName(),
            "description" => $carrier->getDescription(),
            "price" => $carrier->getPrice(),
        ]);

        // On récupere les données
        $cart = $cartService->getCartDetails();

        // On returne un reponse
        return $this->json([
            "isSuccess" => true,
            "data" => $cart,
        ]);
    }
}
