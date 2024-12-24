<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Symfony\UX\Turbo\TurboBundle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatController extends AbstractController
{
    #[Route('/chat', name: 'chat')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $messages = $em->getRepository(Message::class)->findAll();
        $action = count($messages) === 0 ? 'message_replace' : 'message_append';

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $emptyForm = clone $form;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($message);
            $em->flush();

            $block = $this->render('broadcast/' . $action . '.stream.html.twig', [
                'message' => $message->getContent(),
                'form' => $emptyForm,
            ]);

            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                return $block;
            }
        }

        return $this->render('chat/index.html.twig', [
            'form' => $form,
            'message' => $message,
            'messages' => $messages,
        ]);
    }
}
