<?php 

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Loader\Configurator\session;

class WishListService {

   
    public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepo,
        
    )
    {
        $this->session = $requestStack->getSession();
        $this->productRepo = $productRepo;
    }

    /**
     * Son role et de returner et récupére la Wishlist
     *
     * @return void
     */
    public function getWishList()
    {
        // On lui passe un tableau vide par default au panier
        return $this->session->get("wishlist", []);
    }


    /**
     * Mise à jour de la wishlist
     *
     * @param [type] $wishlist
     * @return void
     */
    public function updateWishList($wishlist)
    {
        // On lui passe le panier et un tableau vide par default
        return $this->session->set("wishlist", $wishlist);
    }



    /**
     * Ajouter à la wishlist le produit via son l'id
     *
     * @param [type] $productId
     * @return void
     */
    public function addToWishList($productId)
    {
         // On recupre le panier courant 
        $wishlist = $this->getWishList();

        // Si ce ne pas defini
        if(!isset($wishlist[$productId])){
            // A ce moment la on defini et on passe la valeur 1
            $wishlist[$productId] = 1;
            // On me à jour ce qui a dans la (session)
            $this->updateWishList($wishlist);
        }

    }



    /**
     * Supprime le produit via son l'id 
     *
     * @param [type] $productId
     * @return void
     */
    public function removeToWishList($productId)
    {
        // Pour supprimer un élément du panier on doit d'abord récupérer le panier
        $wishlist = $this->getWishList();
        
        // Es que se defini
        if(isset($wishlist[$productId])){
            // On rétire
            unset($wishlist[$productId]);          
            // Et on fait la mise à jour du panier 
            $this->updateWishList($wishlist);
        }
    }


    /**
     * Cette methode a pour but initialiser et de nettoyer la liste de wishlist
     *
     * @return void
     */
    public function clearProductFromWishList()
    {
        // Et on fait la mise à jour de panier 
        // En lui donne un tableau vide comme ça on initalise le panier
         $this->updateWishList([]);
    }




    /**
     * Affiche les details de la Wishlist
     *
     * @return void
     */
    public function getWishListDetails()
    {
        // A pour but de récupérer les détails du panier

        // On récupérer le panier
        $wishlist = $this->getWishList();
        // On initialise le tablau result
        $result = [];

        foreach ($wishlist as $productId => $quantity){
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
                    'stock' => $product->getStock(),
                ];
            } else {
                // On rétire 
                unset($wishlist[$productId]);
                // Et on met à jour
                $this->updateWishList($wishlist);
            }
        }
        return $result;
    }

}