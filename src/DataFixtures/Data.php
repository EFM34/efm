<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpKernel\KernelInterface;

class Data extends Fixture
{
    /* kernelInterface $appKernel */
    private $appKernel;
    private $rootDir;

    public function __construct(kernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
        $this->rootDir = $appKernel->getProjectDir();
    }


    public function load(ObjectManager $manager): void
    {
        //  PRODUCT
        // Chemain des notre dossier de données
        $filesname = $this->rootDir. '/src/DataFixtures/Data/products.json';
        // On lit les données 
        $data = file_get_contents($filesname);

        // Dans product on a un [tableau] qui a plusieur produits que on récupére
        $products_json = json_decode($data);

        // On crée un tableux vide de products
        $products = [];

        //  On parcur chaque element que on a dans products 
        foreach ($products_json as $product_item) {
            // on créé l'instance du product
            $product = new Product();

            //  On peux modifier les valeurs
            $product->setName($product_item->name)
                    ->setDescription($product_item->description)
                    ->setMoreDescription($product_item->more_description)
                    ->setImageUrls($product_item->imageUrls)
                    ->setSoldePrice($product_item->solde_price * 100)
                    ->setRegularPrice($product_item->regular_price * 100)
            ;

            // On ajoute le produit a notre tableaux 
            $products[] = $product;
            //  On prepare et persiste les données 
            $manager->persist($product);
        }
        // dd($products);
       
        // USER
         // Chemain des notre dossier de données
         $filesname = $this->rootDir. '/src/DataFixtures/Data/users.json';
         // On lit les données 
         $data = file_get_contents($filesname);

         $users_json = json_decode($data);
         $users = [];

         foreach ($users_json as $user_item) {
            $user = new User();
            $user->setFullName($user_item->fullName)
                ->setCivility($user_item->civility)
                ->setEmail($user_item->email)
                ->setPassword("123456")
            ;

            $manager->persist($user);
         }

        // CATEGORY 
        $categories = ["Robes", "Jupes", "Culotte", "Pantalons", "Chemises"];
       
        foreach($categories as $name) {
            $category = new Category();
            $category->setName($name)
            ;
            
            $manager->persist($category);
        }


        $manager->flush();
    }
}
