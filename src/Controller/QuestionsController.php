<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Questions;

use App\Form\QuestionsType;
use App\Repository\QuestionsRepository;
use App\Repository\AnswersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/questions')]
class QuestionsController extends AbstractController
{
    #[Route('/', name: 'questions_index', methods: ['GET'])]
    public function index(QuestionsRepository $questionsRepository): Response
    {
        return $this->render('questions/index.html.twig', [
            'questions' => $questionsRepository->findAll(),
        ]);
    }

    #[Route('/ask-question', name: 'questions_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $user = $this->getUser();

        $question = new Questions();

        // //set foreign key
        $question->setUser($user);

        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('questions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('questions/new.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/individual/{id}', name: 'individual_question', methods: ['GET','POST'])]
    public function selected(Request $request, QuestionsRepository $questionsRepository, AnswersRepository $answersRepository): Response
    {

        $id = $request->get('id');

        // echo $id;
        // die;

        // ajax voter

        if($request->isXmlHttpRequest()) { //if we are receiving a HTTP request
            
            //get the POST data - id
            
            
            $votes = 0; //default
            $questionId = $_POST['id'];
            $voteType = $_POST['voteType'];
            
            $entityManager = $this->getDoctrine()->getManager();
            //get the questions that was liked
            $questionsVote = $entityManager->getRepository(Answers::class)->find($questionId);
            //update the votes to the questionss current count - using the getter
            $votes = $questionsVote->getVotes();
            //update our entity using the setter
            
            if($voteType == 1 ){
                $questionsVote->setVotes($votes + 1);

                $entityManager->flush();
                return $votes + 1;

            }
            
            if($voteType == 0){
                $questionsVote->setVotes($votes - 1);
                
                $entityManager->flush();
                return $votes - 1;


            }

            //TODO: - Add questions id and user id to votes table


        }

        return $this->render('individual_question.html.twig', [
            'questions' => $questionsRepository->find($id),
            'answers' => $answersRepository->findByQuestion($id),
        ]);
    }

    #[Route('/{id}', name: 'questions_show', methods: ['GET'])]
    public function show(Questions $question): Response
    {
        return $this->render('questions/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{id}/edit', name: 'questions_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Questions $question): Response
    {
        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('questions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('questions/edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'questions_delete', methods: ['POST'])]
    public function delete(Request $request, Questions $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('questions_index', [], Response::HTTP_SEE_OTHER);
    }
}
