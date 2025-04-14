<?php 

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Loader\Configurator\session;

class CartService {

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
    public function getCart()
    {
        // On lui passe un tableau vide par default au panier
        return $this->session->get("cart", []);
    }

    public function updateCart($cart)
    {
        // On lui passe le panier et un tableau vide par default
        return $this->session->set("cart", $cart);
    }


    public function addToCart($productId, $count = 1)
    {
        // Ajout product au panier 
        // [
        //     '1' => 2,
        //     '21' => 2
        // ];

        // On recupre le panier courant 
        $cart = $this->getCart();

        // On regarde si ça ne pas vide alors ce que il existe déjà dans le panier
        // Si existe alors on ajoute par deçu la nouvelle quantite par deçu
        if(!empty($cart[$productId])){
            // product exist déjà dans le panier on ajoute avec += du count
            $cart[$productId] += $count;
        } else {
            // Produit n'existe pas on créer le produits
            // On veut ajouter un ellement dans ce panier 
            $cart[$productId] = $count;
        }

        // On me à jour le panier qui est dans la (session)
        $this->updateCart($cart);
    }


    public function removeToCart($productId, $count = 1)
    {
        // Pour supprimer un élément du panier on doit d'abord récupérer le panier
        $cart = $this->getCart();
        
        // On regadre si on a un element dans la panier et si se diferent de null
        if(isset($cart[$productId])){
            // On regarde si ce que on demande de rétire et inferieur ou égale a la quantité quil y'a déjà dans les panier
            if($cart[$productId] <= $count) {
                // On annulle ce qu'il y'a dans le panier pour rétire
                unset($cart[$productId]);
                }else {
                    // On retire des produit du panier 
                    // La quantite et strictement superieur a la quantite qu'on demande de retire du panier
                    $cart[$productId] -= $count;
                }
        
            // Et on fait la mise à jour du panier 
            $this->updateCart($cart);
        }
    }


    /**
     * Cette methode a pour but initialiser et de nettoyer le panier 
     *
     * @return void
     */
    public function clearProductFromCart()
    {
        // Et on fait la mise à jour de panier 
        // En lui donne un tableau vide comme ça on initalise le panier
         $this->updateCart([]);
    }


    public function getCartDetails()
    {
        // A pour but de récupérer les détails du panier

        // On récupérer le panier
        $cart = $this->getCart();
        // On initialise le tablau result
        $result = [
            "items" =>  [],
            "sub_total" => 0,
            // On comptre le nombre des éléménts qu'on a dans le Panier
            "cart_count" => 0,
        ];

        // Je doit calculer a chaque fois le prix total qui sera au départ a 0
        $sub_total = 0;
        foreach ($cart as $productId => $quantity){
            // si le produit est associé à l'id
            $product = $this->productRepo->find($productId);
            // Est ce que ça existe 
            if($product){
                // On calcule le total et on le passe a sub_total la current_sub_total
                $current_sub_total = $product->getSoldePrice() * $quantity;
                // On le passe current_sub_total a sub_total
                $sub_total += $current_sub_total;
                // On passe l'items au resultat
                $result['items'][] = [
                    'product' => [
                        'id' => $product->getId(),
                        'name' => $product->getName(),
                        'slug' => $product->getSlug(),
                        'imageUrls' => $product->getImageUrls(),
                        'soldePrice' => $product->getSoldePrice(),
                        'regularPrice' => $product->getRegularPrice(),
                    ],
                        'quantity' => $quantity,
                        // 'taxe' => 20,
                        // On calcule le prix product qui et en solde fois la quantité
                        'sub_total' => $current_sub_total,
                ];
                /// A chaque tour de boucle je prend le resultat et je vais stocker ce que j'ai omment total
                $result['sub_total'] = $sub_total;
                $result['cart_count'] += $quantity;
               

            } else {
                // Si  l'id ça n'existe pas on retire du panier 
                unset($cart[$productId]);
                // Et on met à jour le panier
                $this->updateCart($cart);
            }
        }
        

        return $result;
    }



}