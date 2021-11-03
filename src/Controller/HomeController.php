<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Questions;
use App\Entity\Answers;

use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    
    /**
     * @Route("/", name="home", methods={"GET", "POST"})
     */
    public function home(Request $request): Response
    {

        // login and admin check 

        $session = $request->getSession();
        $isLoggedIn = $session->get('isLoggedIn');

        $questions = $this->getDoctrine()
             ->getRepository(Questions::class)
             ->findAll();

        // ajax voter
        
        if($request->isXmlHttpRequest()) { //if we are receiving a HTTP request
            
            //get the POST data - id
            
            
            $votes = 0; //default
            $questionId = $_POST['id'];
            $voteType = $_POST['voteType'];
            
            $entityManager = $this->getDoctrine()->getManager();
            //get the questions that was liked
            $questionsVote = $entityManager->getRepository(Questions::class)->find($questionId);
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

            // $repuation = $entityManager->getRepository(Questions::class)
            //             ->            
            // ;

        }
        $isAdmin = 0;

        $user = $this->getUser();
        if($isLoggedIn){
            $admin = $user->getRoles();
            if($admin[0] == "ROLE_ADMIN"){
                $isAdmin = 1;

            }else{
                $isAdmin = 0;
        
            }
        }    


        //identify the twig template
        $view = 'home.html.twig';
            
        // create a model
        $model = array(
            'user' => $user,
            'questions' => $questions,
            'isLoggedIn' => $isLoggedIn,
            'isAdmin' => $isAdmin
        );

        // return the twig template
        return $this->render($view, $model);

    }

    /**
     * @Route("/explore", name="explore", methods={"GET", "POST"})
     */
    public function explore(Request $request): Response
    {

        // login and admin check 

        $session = $request->getSession();
        $isLoggedIn = $session->get('isLoggedIn');
        
        $isAdmin = 0;

        $user = $this->getUser();
        if($isLoggedIn){
            $admin = $user->getRoles();
            if($admin[0] == "ROLE_ADMIN"){
                $isAdmin = 1;

            }else{
                $isAdmin = 0;
        
            }
        } 

        $questions = $this->getDoctrine()
             ->getRepository(Questions::class)
             ->findAll();

             
        // reputation setter
             
        // $votes = $this->getDoctrine()
        //     ->getRepository(Questions::class)
        //     ->find(2);
        // echo '<PRE>';
        // var_dump($votes);
        // die;
        

        // ajax voter
        
        if($request->isXmlHttpRequest()) { //if we are receiving a HTTP request
            
            //get the POST data - id
            
            
            $votes = 0; //default
            $questionId = $_POST['id'];
            $voteType = $_POST['voteType'];
            
            $entityManager = $this->getDoctrine()->getManager();
            //get the questions that was liked
            $questionsVote = $entityManager->getRepository(Questions::class)->find($questionId);
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


        //identify the twig template
        $view = 'explore.html.twig';
            
        // create a model
        $model = array(
            'user' => $user,
            'questions' => $questions,
            'isLoggedIn' => $isLoggedIn,
            'isAdmin' => $isAdmin
        );

        // return the twig template
        return $this->render($view, $model);

    }

    /**
     * @Route("/my-answers", name="my_answers", methods={"GET", "POST"})
     */
    public function myAnswers(Request $request): Response
    {

        // login and admin check 

        $session = $request->getSession();
        $isLoggedIn = $session->get('isLoggedIn');
        

        $isAdmin = 0;

        $user = $this->getUser();
        if($isLoggedIn){
            $admin = $user->getRoles();
            if($admin[0] == "ROLE_ADMIN"){
                $isAdmin = 1;

            }else{
                $isAdmin = 0;
        
            }
        } 
        

        $id = $user->getId();

        $answers = $this->getDoctrine()
             ->getRepository(Answers::class)
             ->findByUser($id);
        
        $questions = $this->getDoctrine()
             ->getRepository(Questions::class)
             ->findAll();
             
        // reputation setter
             
        // $votes = $this->getDoctrine()
        //     ->getRepository(Questions::class)
        //     ->find(2);
        // echo '<PRE>';
        // var_dump($votes);
        // die;
        

        // ajax voter
        
        if($request->isXmlHttpRequest()) { //if we are receiving a HTTP request
            
            //get the POST data - id
            
            
            $votes = 0; //default
            $questionId = $_POST['id'];
            $voteType = $_POST['voteType'];
            
            $entityManager = $this->getDoctrine()->getManager();
            //get the questions that was liked
            $questionsVote = $entityManager->getRepository(Questions::class)->find($questionId);
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


        //identify the twig template
        $view = 'my_answers.html.twig';
            
        // create a model
        $model = array(
            'user' => $user,
            'questions' => $questions,
            'answers' => $answers,
            'isLoggedIn' => $isLoggedIn,
            'isAdmin' => $isAdmin
        );

        // return the twig template
        return $this->render($view, $model);

    }

}
