<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RegisterController extends AbstractController
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher
    ) {}

    #[Route('/register', name: 'app_register_addaccount')]
    public function addAccount(Request $request): Response
    {
        $msg = "";
        $type = "";
        //Créer un objet Account
        $account = new Account();
        //Créer un objet RegisterType (formulaire)
        $form = $this->createForm(RegisterType::class, $account);
        //Récupérer le resultat de la requête
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //Test si le compte n'existe pas
            if(!$this->accountRepository->findOneBy(["email" => $account->getEmail()])) {
                $account->setStatus(false);
                $account->setRoles(["ROLE_USER"]);
                $this->em->persist($account);
                $this->em->flush();
                $msg = "Le compte a été ajouté en BDD";
                $type = "success";
            }
            else {
                $msg = "Les informations email et ou password existe déja";
                $type = "danger";
            }

            $this->addFlash($type,$msg);
        }

        return $this->render('register/addaccount.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/activte/{id}', name: 'app_register_activate')]
    public function activate(int $id): Response{
        
        $account = $this->accountRepository->find($id);

        // On vérifie si le compte est déjà activé
        if($account->getStatus() === true){
            $this->addFlash('info', 'Le compte est déjà activé.');
            return $this->redirectToRoute('app_register_addaccount');
        }

        // On modifie la valeur du status à true
        $account->setStatus(true);
        $this->em->flush();
        $this->addFlash('success', 'Le compte a été activé avec succès.');
        return $this->redirectToRoute('app_register_addaccount');
    }
}
