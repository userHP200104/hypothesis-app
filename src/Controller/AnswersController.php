<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Questions;
use App\Entity\Answers;

use App\Form\AnswersType;
use App\Repository\AnswersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/answers')]
class AnswersController extends AbstractController
{
    #[Route('/', name: 'answers_index', methods: ['GET'])]
    public function index(AnswersRepository $answersRepository): Response
    {
        return $this->render('answers/index.html.twig', [
            'answers' => $answersRepository->findAll(),
        ]);
    }
    
    #[Route('/{id}', name: 'answers_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        
        $id = $request->get('id');
        
        $question = $this->getDoctrine()
        ->getRepository(Questions::class)
        ->find($id);
        
        $user = $this->getUser();
        // $question = $questions->getId();
        
        // // echo '<PRE>';
        // // var_dump($question);
        // // die;
                    
        $answer = new Answers();
                    
        // //set foreign key
        $answer->setUser($user);
        $answer->setQuestion($question);

        $form = $this->createForm(AnswersType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($answer);
            $entityManager->flush();

            return $this->redirectToRoute('individual_question', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('answers/new.html.twig', [
            'answer' => $answer,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'answers_show', methods: ['GET'])]
    public function show(Answers $answer): Response
    {
        return $this->render('answers/show.html.twig', [
            'answer' => $answer,
        ]);
    }

    #[Route('/edit/{id}', name: 'answers_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Answers $answer): Response
    {
        $form = $this->createForm(AnswersType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('answers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('answers/edit.html.twig', [
            'answer' => $answer,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'answers_delete', methods: ['POST'])]
    public function delete(Request $request, Answers $answer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$answer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($answer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('answers_index', [], Response::HTTP_SEE_OTHER);
    }
}
