<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Twig\Environment;

class Mailer
{
    private $mailer;
    private $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * Envoie un email
     *
     * @param string $to Email du destinataire
     * @param string $subject Sujet de l'email
     * @param string $template Template HTML du contenu
     * @param array $context Variables à passer au template
     */
    public function send(
        string $to,
        string $subject,
        string $htmlTemplate,
        array $context = []
    ): void
    {
        $email = (new Email())
            ->from(new Address('noreply@votresite.com', 'Votre Site'))
            ->to($to)
            ->subject($subject)
            ->html($htmlTemplate);

        $this->mailer->send($email);
    }

    /**
     * Envoie un email avec template Twig
     */
    public function sendTemplateMail(
        string $to,
        string $subject,
        string $templateName,
        array $context = []
    ): void
    {
        // Vous aurez besoin d'injecter TwigEnvironment dans le constructeur
        $html = $this->twig->render($templateName, $context);
        
        $this->send($to, $subject, $html);
    }
}