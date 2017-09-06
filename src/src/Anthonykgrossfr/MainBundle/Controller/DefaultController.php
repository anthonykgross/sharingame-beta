<?php

namespace Anthonykgrossfr\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Anthonykgrossfr\MainBundle\Entity\Contact as E_Contact;

class DefaultController extends Controller
{
    public function indexAction() {
        $request    = $this->getRequest();
        $errors     = array();
        $success    = array();
        
        $em = $this->getDoctrine()->getManager();
        
        if($request->getMethod() == "POST"){
            $email          = $request->get("email", null);
            
            if(is_null($email) || strlen($email)==0){
                $errors[] = "Veuillez renseigner votre adresse email";
            }
            
            $user = $em->getRepository("AnthonykgrossfrMainBundle:Contact")->findOneBy(array("email"=>$email));
            if($user){
                $errors[] = "Adresse email déjà enregistrée.";
            }
            else{
                $contact = new E_Contact();
                $contact->setEmail($email);
                $em->persist($contact);
                $em->flush();
            }
            if(count($errors)==0){
                
                try{
                    $message = \Swift_Message::newInstance()
                        ->setSubject("[beta.sharingame.com] - Inscription à la béta")
                        ->setFrom('no-reply@sharingame.com')
                        ->setReplyTo(array($email => $email))
                        ->setTo('anthony.k.gross@gmail.com')
                        ->setBody($this->renderView('AnthonykgrossfrMainBundle:Default:email.html.twig', array(
                            'email'     => $email
                        )), 'text/html')
                    ;
                    $this->get('mailer')->send($message);

                    $message = \Swift_Message::newInstance()
                        ->setSubject("[beta.sharingame.com] - Inscription  de $email")
                        ->setFrom('no-reply@sharingame.com')
                        ->setReplyTo(array("anthony.k.gross@gmail.com" => "anthony.k.gross@gmail.com"))
                        ->setTo('anthony.k.gross@gmail.com')
                        ->setBody($this->renderView('AnthonykgrossfrMainBundle:Default:email.html.twig', array(
                            'email'     => $email
                        )), 'text/html')
                    ;
                    $this->get('mailer')->send($message);
                    $success[] = "Merci pour votre inscription";
                }
                catch (\Exception $e){
                    $errors[] = "Adresse email invalide.";
                }
                
            }
        }
        
        return $this->render('AnthonykgrossfrMainBundle:Default:index.html.twig', array(
            "errors" => $errors,
            "success" => $success
        ));
    }
}
