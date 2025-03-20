<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // Lors que on sumet le formulaire et que il y'a pas d'erreur
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // A ce moment on mofifie le mdp de user
            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));


            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('ensemblefredericmistral@gmail.com', 'E.F.M'))
                    ->to((string) $user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        // de que on a l'id de la réquette 
        $id = $request->query->get('id');
        // Si l'id n'existe pas 
        if (null === $id) {
            // on rédirige ver la page d'inscription
            return $this->redirectToRoute('app_register');
        }

        // Si ça existe on récupér l'utilisateur
        $user = $userRepository->find($id);

        // Si on truve pas l'utilisateur 
        if (null === $user) {
             // on rédirige toujour ver la page d'inscription
            return $this->redirectToRoute('app_register');
        }
        // Si on et encore la ce que on a bien trouvel l'utilisateur
        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            // Dans ce cas on peu envoyer l'email de confirmation 
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        // Si il y'a une erreur dans ce cas
        } catch (VerifyEmailExceptionInterface $exception) {
            // On envoiun message flash
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
