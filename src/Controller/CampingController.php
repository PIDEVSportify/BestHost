<?php

namespace App\Controller;

use App\Entity\Camping;
use App\Entity\Offre;
use App\Entity\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Image;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
//use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CampingController extends AbstractController
{
    /**
     * @Route("/lister_sites", name="Afficher_site")
     */
    public function index(Request $request)
    {
        $get_sites=$this->getDoctrine()->getRepository(Camping::class);
        $site=$get_sites->findAll();
        $liste=array(array('site'=>$site),array('offre'=>$this->getDoctrine()->getRepository(Offre::class)->findAll()));
        $camping =new Camping();
        $form = $this->createFormBuilder($camping)
            ->add('id', NumberType::class)
            ->add('localisation_camping', TextType::class)
            ->add('description_camping', TextType::class)
            ->add('type_camping', TextType::class)
            ->add('imageFile', FileType::class,[
        'mapped' => false
    ])
            ->add('find_offre', NumberType::class)
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );

            $get_sites1=$this->getDoctrine()->getRepository(Offre::class);
            $find=$get_sites1->find($camping->getFindOffre());
            $entityManager = $this->getDoctrine()->getManager();
            if($find){
                $camping->setImageCamping($newFilename);
                $camping->setOffreId($find);
                $entityManager->persist($camping);
                $entityManager->flush();
                }
            else{
                $camping->setImageCamping($newFilename);
                $camping->setOffreId(Null);
                $entityManager->persist($camping);
                $entityManager->flush();
              }
            return $this->redirectToRoute('Afficher_site');
        }

        return $this->render('camping/lister_sites.html.twig',
            ['liste' => $liste,'formcamping' => $form->createView()]);
    }

    /**
     * @Route("/modifier_camping", name="Modifier_camping")
     */
    public function modifier_camping(Request  $request1)
    {
        dump($request1);
        $entityManager = $this->getDoctrine()->getManager();
        if($request1->request->get('id')){
        $camping = $entityManager->getRepository(Camping::class)->find($request1->request->get('id'));
        if (!$camping) {
            throw $this->createNotFoundException(
                'No site found for id ' . $request1->request->get('id')
            );
        }}
        else{
            $camping = $entityManager->getRepository(Camping::class)->find($request1->request->get('form')['id']);
            dump($camping);
        }
        $form = $this->createFormBuilder($camping)
            ->add('id', NumberType::class)
            ->add('localisation_camping', TextType::class)
            ->add('description_camping', TextType::class)
            ->add('type_camping', TextType::class)
            ->add('imageFile', FileType::class, [
                'mapped' => false
            ])
            ->add('find_offre', NumberType::class)
            ->getForm();
        $form->handleRequest($request1);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $get_class = $this->getDoctrine()->getRepository(Offre::class);
            $find = $get_class->find($request1->request->get('form')['find_offre']);
            $camping->setImageCamping($newFilename);
            if (!$find) {

                $entityManager->flush();
                $this->addFlash("warning", "No Offer found for id_offer ,and the site has been updated");
                $this->redirectToRoute('Afficher_site');
            }
            $entityManager->flush();
            return $this->redirectToRoute('Afficher_site');
        }
        return $this->render('camping/modifier_site.html.twig',
            ['formcamping' => $form->createView()]);
    }

    /**
     * @Route("/supprimer_camping", name="Supprimer_camping")
     */


    public function supprimer_camping(Request  $request)
    {
        if ($request->request->get('id')>0) {
            $entityManager = $this->getDoctrine()->getManager();
            $site = $entityManager->getRepository(Camping::class)->find($request->request->get('id'));
            $entityManager->remove($site);
            $entityManager->flush();
            return $this->redirectToRoute('Afficher_site');
        } else {
            throw $this->createNotFoundException(
                'something is wrong for id ' . $request->request->get('id')
            );
        }
    }

}
