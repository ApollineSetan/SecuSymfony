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
use App\Service\UtilsService;

final class RegisterController extends AbstractController
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly UtilsService $utilsService
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

    #[Route('/activate/{id}', name:'app_register_activate')]
    public function activate(mixed $id) :Response {
        try {
            $id = $this->utilsService->decodeBase64($id);
            if(is_numeric($id)) {
                $account = $this->accountRepository->find($id);
                if(!$account->isStatus()) {
                    $account->setStatus(true);
                    $this->em->persist($account);
                    $this->em->flush();
                }
            }
        } catch (\Exception $e) {
            $this->addFlash("warning", $e->getMessage());
        }
        //Redirection vers la page Accueil
        return $this->redirectToRoute('app_register_addaccount');
    }
}