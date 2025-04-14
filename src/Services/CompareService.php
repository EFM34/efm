<?php 

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Loader\Configurator\session;

class CompareService {

    public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepo,
        
    )
    {
        $this->session = $requestStack->getSession();
        $this->productRepo = $productRepo;
    }

    /**
     * Son role et de returner et récupére le panier
     *
     * @return void
     */
    public function getCompare()
    {
        // On lui passe un tableau vide par default au panier
        return $this->session->get("compare", []);
    }

    public function updateCompare($compare)
    {
        // On lui passe le panier et un tableau vide par default
        return $this->session->set("compare", $compare);
    }


    public function addToCompare($productId)
    {
         // On recupre le panier courant 
        $compare = $this->getCompare();

        // Si ce ne pas defini
        if(!isset($compare[$productId])){
            // A ce moment la on defini et on passe la valeur 1
            $compare[$productId] = 1;
            // On me à jour ce qui a dans la (session)
            $this->updateCompare($compare);
        }

    }


    public function removeToCompare($productId)
    {
        // Pour supprimer un élément du panier on doit d'abord récupérer le panier
        $compare = $this->getCompare();
        
        // Es que se defini
        if(isset($compare[$productId])){
            // On rétire
            unset($compare[$productId]);          
            // Et on fait la mise à jour du panier 
            $this->updateCompare($compare);
        }
    }


    /**
     * Cette methode a pour but initialiser et de nettoyer le panier 
     *
     * @return void
     */
    public function clearProductFromCompare()
    {
        // Et on fait la mise à jour de panier 
        // En lui donne un tableau vide comme ça on initalise le panier
         $this->updateCompare([]);
    }


    public function getCompareDetails()
    {
        // A pour but de récupérer les détails du panier

        // On récupérer le panier
        $compare = $this->getCompare();
        // On initialise le tablau result
        $result = [];

        foreach ($compare as $productId => $quantity){
            // si le produit est associé à l'id
            $product = $this->productRepo->find($productId);
            // Est ce que ça existe 
            if($product){
                // On stocke le resultat u product
                $result[] = [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'slug' => $product->getSlug(),
                    'imageUrls' => $product->getImageUrls(),
                    'soldePrice' => $product->getSoldePrice(),
                    'regularPrice' => $product->getRegularPrice(),
                ];
            } else {
                // On rétire 
                unset($compare[$productId]);
                // Et on met à jour
                $this->updateCompare($compare);
            }
        }
        return $result;
    }



}