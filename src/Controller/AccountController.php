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

    #[Route('/compte/adresse/ajouter/{id}', name: 'app_account_address_form', defaults: ['id'=>null])]
    public function addressForm(Request $request, $id): Response
    {
        if ($id){
            $address = $this->entityManager->getRepository(Address::class)->findOneById($id);
            if ($address){
                if ($address->getUser() != $this->getUser()){
                    $this->addFlash(
                        'warning',
                        "Vous n'avez pas les droit necessaire pour modifier cette adresse."
                    );
                    return $this->redirectToRoute('app_account_addresses');
                }
            }else{
                $this->addFlash(
                    'warning',
                    "Pas d'adresse disponble avec cet id."
                );
                return $this->redirectToRoute('app_account_addresses');
            }

        }else{
            dd(__LINE__);

            $address = new Address();
            $address ->setUser($this->getUser());
        }


        $addressForm = $this->createForm(AddressUserType::class, $address);
        $addressForm->handlerequest($request);

        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            $this->entityManager->persist($address);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'Votre adresse est correectement sauvegardée.'
            );

            return $this->redirectToRoute('app_account_addresses');
        }

        return $this->render('account/addressForm.html.twig', [
            'addressForm' => $addressForm->createView(),
        ]);
    }
}
