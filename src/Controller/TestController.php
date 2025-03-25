<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EmailService;

class TestController extends AbstractController {

    public function __construct(
        private readonly EmailService $emailService
    ) {}

    #[Route('/test', name:'app_test_sendEmail')]
    public function email() {

        $imageUrl = 'https://external-preview.redd.it/oPDLQXls4_bT3MBWwUl95V7JnYP7YBs7ofHESFM9-Ic.jpg?auto=webp&s=0a1145aa43cfbfc9d9f61be58888645a9feeb7da';

        $template = $this->render('email/template.html.twig', [
            'subject'=> "Objet du message",
            'body' => "Contenu"
        ]);

        $this->emailService->sendEmail("setan.apolline@gmail.com", "YOU'VE BEEN HACKED", "YOU'VE BEEN HACKED", $imageUrl, $template->getContent());
        return new Response ('Mail envoyé');
    }
}

