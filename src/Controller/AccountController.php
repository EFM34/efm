<?php

namespace App\Controller;

use App\Entity\Address;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(AddressRepository $addressRepository): Response
    {
         // Infos user qui est connecter au moment qu'on se trouve sur la méthode
         $user = $this->getUser();

         // On récupre l'address que d'un user
         $addresses = $addressRepository->findByUser($user);
         
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
             // et on l'affiche
             'addresses' => $addresses,
        ]);
    }

}
