<?php

namespace App\Controller\Account;

use App\Form\ModifierPwdType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class PasswordController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct( EntityManagerInterface $entityManager,)
    {
        $this->entityManager = $entityManager;
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
}
