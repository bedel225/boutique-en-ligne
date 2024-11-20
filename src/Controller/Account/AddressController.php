<?php

namespace App\Controller\Account;

use App\Entity\Address;
use App\Form\AddressUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AddressController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct( EntityManagerInterface $entityManager,)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/adresses', name: 'app_account_addresses')]
    public function index(): Response
    {
        $addresses = $this->entityManager->getRepository(Address::class)->findAll();
        return $this->render('account/Address/index.html.twig', [
            'addresses' => $addresses,
        ]);
    }

    #[Route('/compte/adresse/delete/{id}', name: 'app_account_address_delete')]
    public function delete($id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if (!$address || $address->getUser() != $this->getUser()) {

            $this->addFlash(
                'warning',
                "Vous n'avez pas les droit necessaire pour supprimer cette adresse."
            );
            return $this->redirectToRoute('app_account_addresses');

        }

        $this->entityManager->remove($address);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_account_addresses');
    }

    #[Route('/compte/adresse/ajouter/{id}', name: 'app_account_address_form', defaults: ['id'=>null])]
    public function form(Request $request, $id): Response
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

        return $this->render('account/Address/form.html.twig', [
            'addressForm' => $addressForm->createView(),
        ]);
    }
}
