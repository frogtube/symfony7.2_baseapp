<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, Mailer $mailer): Response
    {
        $contactDTO = new ContactDTO;

        $form = $this->createForm(ContactType::class, $contactDTO);
        
        $form->handleRequest($request);

        // TODO: bug fix les messages d'erreur ne sont pas affichés sur le formulaire

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            
            try {
                $mailer->sendTemplateMail(
                    $_ENV['CONTACT_EMAIL'],
                    'Nouveau contact sur le formulaire du site',
                    'emails/contact.html.twig',
                    [
                        'name' => $data->getName(),
                        'email' => $data->getEmail(),
                        'message' => $data->getMessage()
                    ]
                );
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi du message.');
                return $this->redirectToRoute('app_contact');
            }

            $this->addFlash('success', 'Votre message a été envoyé avec succès !');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}