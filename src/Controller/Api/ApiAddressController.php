<?php

namespace App\Controller\Api;

use App\Entity\Address;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/api')]
final class ApiAddressController extends AbstractController
{
    #[Route('/address', name: 'app_post_address', methods: ['POST'])]
    public function index(Request $req, 
    AddressRepository $addressRepository, EntityManagerInterface $em): Response

    {
        // Infos user qui est connecter au moment qu'on se trouve sur la méthode
        $user = $this->getUser();

        // Si se le cas contraire ce que il y'a un erreur et il fudra preciser cela
        if(!$user){
            return $this->json([
                // C'est ne pas ok 
                "isSuccess" => false,
                // Pas d'autorisation a cette requette
                "message" => "Pas d'autorisation !",
                "data" => []
            ]);
        }
        // Si on arrive bien a récupére a ce moment ce que tous est ok 


        // On récupére les données
        $formData = $req->getPayload();

        $address =  new Address();
        $address->setName($formData->get('name'))
                ->setClientName($formData->get('client_name'))
                ->setStreet($formData->get('street'))
                ->setCity($formData->get('city'))
                ->setPostalCode($formData->get('postal_code'))
                ->setState($formData->get('state'))
                ->setUser($user)
                ;

                $em->persist($address);
                $em->flush();

            // On récupre toutes les l'address que d'un user
            $addresses = $addressRepository->findByUser($user);

            // On parcour les addresses
            foreach ($addresses as $key => $address) {
                // On met a null l'utilisateur 
                $address->setUser(null);
                // Et on affiche l'address
                $addresses[$key] = $address;
            }

        return $this->json([
            // Si on arrive bien a récupére ce que tous est ok 
            "isSuccess" => true,
            "data" => $addresses
        ]);
    }

    #[Route('/address/{id}', name: 'app_api_put_address', methods: ['PUT'])]
    public function update($id, Request $req, 
    AddressRepository $addressRepository, EntityManagerInterface $em): Response

    {
        // Infos user qui est connecter au moment qu'on se trouve sur la méthode
        $user = $this->getUser();

        // Si se le cas contraire ce que il y'a un erreur et il fudra preciser cela
        if(!$user){
            return $this->json([
                // C'est ne pas ok 
                "isSuccess" => false,
                // Pas d'autorisation a cette requette
                "message" => "Pas d'autorisation !",
                "data" => []
            ]);
        }


        // On doit récupéere l'addresse qui souhaite supprimer
        $address = $addressRepository->findOneById($id);

         // Si on arrive pa sa récupére l'addrese on envois un message d'erreur
         if(!$address){
            return $this->json([
                // C'est ne pas ok 
                "isSuccess" => false,
                // Pas d'autorisation a cette requette
                "message" => "Adresse non trouvée !",
                "data" => []
            ]);
        }

        // On regarde si l'addrese apartien a l'user qui veut la supprimer
        if($user !== $address->getUser()){
            // On returne un message d'erreur 
            return $this->json([
                // C'est ne pas ok 
                "isSuccess" => false,
                // Pas d'autorisation a cette requette
                "message" => "Pas d'autorisation !",
                "data" => []
            ]);
        }
        
        // start update
        // On récupére les données que on utilise dans notre formData
        $formData = $req->getPayload();
        $address->setName($formData->get('name'))
                ->setClientName($formData->get('client_name'))
                ->setStreet($formData->get('street'))
                ->setCity($formData->get('city'))
                ->setPostalCode($formData->get('postal_code'))
                ->setState($formData->get('state'))
            ;

        $em->persist($address);
        $em->flush();

        // Et on returne la liste de toute les addresses
        $addresses = $addressRepository->findByUser($user);
        // On parcour les addresses
        foreach ($addresses as $key => $address) {
            // On met a null l'utilisateur 
            $address->setUser(null);
            // Et on affiche l'address
            $addresses[$key] = $address;
        }

        return $this->json([
            // Si on arrive bien a récupére ce que tous est ok 
            "isSuccess" => true,
            "data" => $addresses
        ]);
    }
    

    #[Route('/address/{id}', name: 'app_api_delete_address', methods: ['DELETE'])]
    public function delete($id, Request $req, 
    AddressRepository $addressRepository, EntityManagerInterface $em): Response

    {
        // Infos user qui est connecter au moment qu'on se trouve sur la méthode
        $user = $this->getUser();

        // Si se le cas contraire ce que il y'a un erreur et il fudra preciser cela
        if(!$user){
            return $this->json([
                // C'est ne pas ok 
                "isSuccess" => false,
                // Pas d'autorisation a cette requette
                "message" => "Pas d'autorisation !",
                "data" => []
            ]);
        }


        // On doit récupéere l'addressequi souhaite supprimer
        $address = $addressRepository->findOneById($id);

         // Si on arrive pa sa récupére l'addrese on envois un message d'erreur
         if(!$address){
            return $this->json([
                // C'est ne pas ok 
                "isSuccess" => false,
                // Pas d'autorisation a cette requette
                "message" => "Adresse non trouvée !",
                "data" => []
            ]);
        }

        // On regarde si l'addrese apartien a l'user qui veut la supprimer
        if($user !== $address->getUser()){
            // On returne un message d'erreur 
            return $this->json([
                // C'est ne pas ok 
                "isSuccess" => false,
                // Pas d'autorisation a cette requette
                "message" => "Pas d'autorisation !",
                "data" => []
            ]);
        }

        // Si on et pas dans un des ces cas la 
        // Se que l'address lui apartien et que il a droit de la supprimer
        $em->remove($address);
        $em->flush();

        // Et on returne la liste de toute les addresses
        $addresses = $addressRepository->findByUser($user);
        
        // On parcour les addresses
        foreach ($addresses as $key => $address) {
            // On met a null l'utilisateur 
            $address->setUser(null);
            // Et on affiche l'address
            $addresses[$key] = $address;
        }

        return $this->json([
            // Si on arrive bien a récupére ce que tous est ok 
            "isSuccess" => true,
            "data" => $addresses
        ]);
    }
}
