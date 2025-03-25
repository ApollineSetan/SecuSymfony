<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService 
{
    private PHPMailer $mailer;

    public function __construct(
            private readonly string $emailUser,
            private readonly string $emailPassword,
            private readonly string $emailSmtp,
            private readonly int $emailPort,
    ) {
        $this->mailer = new PHPMailer(true);
    }

    /**
     * Méthode pour envoyer des emails
     * @param string $receiver param qui reçoit l'adresse du destinataire du mail
     * @param string $subject param qui reçoit l'objet du mail
     * @param string $body param qui reçoit le corps du mail
     * @return void ne retourne rien
     */

    public function sendEmail(string $receiver, string $subject, string $body, string $imageUrl): void {
        try {
            $this->config();
            $this->mailer->setFrom($this->emailUser, 'Hackerman');
            $this->mailer->addAddress($receiver);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            if ($imageUrl) {
                $body .= '<br><img src="' . $imageUrl . '" alt="Image">';
            }
            $this->mailer->Body = $body;
            $this->mailer->send();
        } catch (Exception $e) {
            echo "Le mail n'a pas été envoyé" . $this->mailer->ErrorInfo;
        }
    }


    private function config(): void {
        $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->mailer->isSMTP();
        $this->mailer->Host = $this->emailSmtp;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $this->emailUser;
        $this->mailer->Password = $this->emailPassword;
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->Port = $this->emailPort;
    }
}