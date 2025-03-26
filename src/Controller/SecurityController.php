<?php

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController {
    public function __construct(
        private readonly AccountRepository $accountRepository
    ) {}

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/security.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path:'/verif', name:'app_security_verif')]
    public function verif(): Response {
        $user = $this->getUser();
        if($user->isStatus() == false){
            return $this->redirectToRoute('app_logout');
        }
        return $this->redirectToRoute('app_product_index');
    }

    #[Route(path:'/logout', name: 'app_logout')]
    public function logout(): void {
        throw new \LogicException("This method can be blank - it will be intercepted by the logout key on your firewall.");
    }

}

