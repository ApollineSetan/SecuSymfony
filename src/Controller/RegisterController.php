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
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RegisterController extends AbstractController
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly EntityManagerInterface $em,
        private readonly ValidatorInterface $validator
    ) {}

    #[Route('/register', name: 'app_register_addaccount')]
    public function addAccount(Request $request, ValidatorInterface $validator): Response
    {
        $msg = "";
        $type = "";
        // Créer un objet Account
        $account = new Account();
        // Créer un objet RegisterType (notre formulaire)
        $form = $this->createForm(RegisterType::class, $account);
        // Récupérer le résultat de la requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $validator->validate($account);
            // Teste si l'entité est valide (validation)
            if (count($errors) > 0){
                $msg = $errors[0]->getMessage();
                $type = "warning";
            } else {
            // Teste si le compte n'existe pas
            if (!$this->accountRepository->findOneBy(["email" => $account->getEmail()])) {
                $this->em->persist($account);
                $this->em->flush();
                $msg = "Le message a été ajouté en BDD.";
                $type = "success";
            } else {
                $msg = "Les informations email et/ou mot de passe existe déjà.";
                $type = "danger";
            }
        }
            $this->addFlash($type, $msg);
        }
        return $this->render('register/addaccount.html.twig', [
            'form' => $form
        ]);
    }
}
