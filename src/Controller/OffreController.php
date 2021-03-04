<?php

namespace App\Controller;

use App\Entity\Offre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
//use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class OffreController extends AbstractController
{
    /**
     * @Route("/lister_offres", name="Afficher_offre")
     */

    public function index(Request $request)
    {
        dump($request);
         if($request->request->get('id')>0){
             return $this->render('offre/modifier_offre.html.twig',['request' => $request]);
         }
        $offre =new Offre();
        $form = $this->createFormBuilder($offre)
            ->add('id', NumberType::class)
            ->add('nombre_places_offre', NumberType::class)
            ->add('date_debut_offre', DateType::class)
            ->add('date_fin_offre', DateType::class)
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offre);
            $entityManager->flush();
            return $this->redirectToRoute('Afficher_offre');

        }
        $get_offre=$this->getDoctrine()->getRepository(Offre::class);
        $offre=$get_offre->findAll();
        return $this->render('offre/lister_offres.html.twig',['offre' => $offre,'formoffre' => $form->createView()]);
    }

    /**
     * @Route("/modifier_offre",name="Modifier_offre")
     */

    public function modifier_offre(Request  $request1)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $offre = $entityManager->getRepository(Offre::class)->find($request1->request->get('save'));
        if (!$offre) {
            throw $this->createNotFoundException(
                'No Offre found for id ' . $request1->request->get('save')
            );
        }
        $offre->setId($request1->request->get('Id'));
        $offre->setNombrePlacesOffre($request1->request->get('Nombre'));
        $date_debut = new \DateTime($request1->request->get('debut'));
        $date_fin = new \DateTime($request1->request->get('fin'));
        $offre->setDateDebutOffre($date_debut);
        $offre->setDateFinOffre($date_fin);
        $this->addFlash("success", "The offer has been updated");
        $entityManager->flush();
        return $this->redirectToRoute('Afficher_offre');
    }

    /**
     * @Route("/supprimer_offre", name="Supprimer_offre")
     */

    public function supprimer_offre(Request  $request)
    {
        if($request->request->get('id')>0 ){
            $entityManager = $this->getDoctrine()->getManager();
            $class = $entityManager->getRepository(Offre::class)->find($request->request->get('id'));
            $entityManager->remove($class);
            $entityManager->flush();
            return $this->redirectToRoute('Afficher_offre');
        }
        else{
            throw $this->createNotFoundException(
                'something is wrong for id '.$request->request->get('id')
            );
        }
    }
}
