<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressUserType;
use App\Form\ModifierPwdType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct( EntityManagerInterface $entityManager,)
    {
       $this->entityManager = $entityManager;
    }
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
        ]);
    }

    #[Route('/compte/modifier-mot-de-passe', name: 'app_modifier_pwd')]
    public function modifier(Request $request,  UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ModifierPwdType::class, $user, [
            'passwordHasher' => $passwordHasher,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->flush();
            $this->addFlash(
                'success',
                'Votre mot de passe a été modifié avec succes'
            );

            return $this->redirectToRoute('app_login');
        }
        return $this->render('account/modifier_pwd.html.twig', [
            'modifierPwdForm' => $form->createView(),
        ]);
    }

    #[Route('/compte/adresses', name: 'app_account_addresses')]
    public function addresses(): Response
    {
        $addresses = $this->entityManager->getRepository(Address::class)->findAll();
        return $this->render('account/addresses.html.twig', [
            'addresses' => $addresses,
        ]);
    }

    #[Route('/compte/adresse/ajouter', name: 'app_account_address_form')]
    public function addressForm(Request $request): Response
    {
        $address = new Address();
        $address ->setUser($this->getUser());

        $addressForm = $this->createForm(AddressUserType::class, $address);
        $addressForm->handlerequest($request);

        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            $this->entityManager->persist($address);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'Votre adresse a été sauvegardé avec succes'
            );

            return $this->redirectToRoute('app_account_addresses');
        }

        return $this->render('account/addressForm.html.twig', [
            'addressForm' => $addressForm->createView(),
        ]);
    }
}
