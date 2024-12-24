<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Component\Mercure\Update;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatController extends AbstractController
{
    #[Route('/chat', name: 'chat')]
    public function index(Request $request, EntityManagerInterface $em, HubInterface $hub): Response
    {
        $messages = $em->getRepository(Message::class)->findAll();

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $emptyForm = clone $form;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($message);
            $em->flush();

            $form = $emptyForm;
        }

        return $this->render('chat/index.html.twig', [
            'form' => $form,
            'message' => $message,
            'messages' => $messages,
        ]);
    }
}
