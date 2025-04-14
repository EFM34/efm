<?php

namespace App\Controller;

use App\Services\CompareService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CompareController extends AbstractController
{
    public function __construct(private CompareService $compareService)
    {
        $this->compareService = $compareService;
    }

    #[Route('/compare', name: 'app_compare')]
    public function index(): Response
    {
        //  On récupére les données
        $compare = $this->compareService->getCompareDetails();

        // On encode le panier en json ici et on le passe la view
        $compare_json = json_encode($compare);

        return $this->render('compare/index.html.twig', [
            'controller_name' => 'CompareController',
            'compare' => $compare,
            'compare_json' => $compare_json
        ]);
    }


    #[Route('/compare/add/{productId}', name: 'app_add_to_compare')]
    public function addToCompare(string $productId): Response
    {
        // On ajoute au compare
       $this->compareService->addToCompare($productId);
        //  On récupére les détails 
        $compare = $this->compareService->getCompareDetails();
        
        // return $this->redirectToRoute('app_compare');

        // On return du json 
        return $this->json($compare);
    }


    #[Route('/compare/remove/{productId}', name: 'app_remove_to_compare')]
    public function removeToCompare(string $productId): Response
    {
        // On supprime le produit du compare
       $this->compareService->removeToCompare($productId);
         //  On récupére les détails
         $compare = $this->compareService->getCompareDetails();
        
         // On return du json 
         return $this->json($compare);
    }



    
    #[Route('/compare/get', name: 'app_get_compare')]
    public function getCompare(): Response
    {
        //  On récupére les détails
        $compare = $this->compareService->getCompareDetails();
        
         // On return du json 
         return $this->json($compare);
    }
}
