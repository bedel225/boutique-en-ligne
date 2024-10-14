<?php

namespace App\Controller;

use App\Form\ModifierPwdType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
        ]);
    }

    #[Route('/modifier_pwd', name: 'app_modifier_pwd')]
    public function modifier(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passWordHasher): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ModifierPwdType::class, $user, [
            'password_hasher' => $passWordHasher,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();
        }
        return $this->render('account/modifier_pawd.html.twig', [
            'modifierPwdForm' => $form->createView(),
        ]);
    }
}
