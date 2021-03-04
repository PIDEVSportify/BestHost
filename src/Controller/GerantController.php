<?php

namespace App\Controller;
use App\Entity\Gerant;
use App\Form\GerantType;
//use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GerantController extends AbstractController
{
    /**
     * @Route("/gerant", name="gerant",methods={"GET"})
     */
    public function index(): Response
    {
        $gerants= $this->getDoctrine()->getRepository(Gerant::class)->findAll();
        return $this->render('gerant/listGerant.html.twig', array('gerants' => $gerants));
    }

    /**
     * @return Response
     * @Route ("/add",name="addGerant")
     */
    public function addGerant(Request $request){
        $gerant=new Gerant();
        $form=$this->createForm(GerantType::class,$gerant);
        $form->add('ajouter',SubmitType::class,['attr'=>
        ['class'=>'btn_3']]);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($gerant);
            $em->flush();
            return $this->redirectToRoute('gerant');
        }
        return $this->render("gerant/addGerant.html.twig",['form'=>$form->createView()]);
    }
    public function showGerant(){

    }
}
