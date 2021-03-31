<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\MaisonHoteRepository;
use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;




use phpDocumentor\Reflection\Types\Null_;

use App\Entity\MaisonHote;
use App\Entity\MaisonImages;
use App\Entity\MaisonRecherche;
use App\Form\ContactType;
use App\Form\MaisonHoteType;
use App\Form\MaisonRechercheType;
use App\Notification\ContactNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\Return_;
use Symfony\Component\DomCrawler\Image;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;
use PhpParser\Node\Expr\AssignOp\Concat;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;

class MaisonHoteController extends AbstractController
{



    /**
     * @Route("/maison_hote", name="maison_hote_list" ,methods={"GET","POST"})
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $search = new MaisonRecherche();

        $form = $this->createForm(MaisonRechercheType::class, $search);

        $form->handleRequest($request);

        $maison = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $search->getNom();
            if ($nom != "")
                $maison = $this->getDoctrine()->getRepository(MaisonHote::class)->findBy(['nom' => $nom]);
            else {
                $donnees = $this->getDoctrine()->getRepository(MaisonHote::class)->findAll();
                $maison = $paginator->paginate(

                    $donnees,
                    $request->query->getInt('page', 1),
                    5

                );
            }
        }

        //  $maison = $this->getDoctrine()->getRepository(MaisonHote::class)->findall($search);


        return $this->render('maison_hote/index.html.twig', [
            'maisons' => $maison,
            'form' => $form->createView()

        ]);
    }

    /**
     * @IsGranted("ROLE_GERANT_MAISON_HOTE","ROLE_ADMIN")
     * @Route("/backmaison", name="backmaison" ,methods={"GET","POST"})
     */
    public function voirmaison()
    {
        $maison = new MaisonHote;
        $maison = $this->getDoctrine()->getRepository(MaisonHote::class)->findall();
        return $this->render('maison_hote/backmaison.html.twig', [
            'maisons' => $maison

        ]);
    }
    /**
     * @IsGranted("ROLE_GERANT_MAISON_HOTE","ROLE_ADMIN")
     * @Route("/backmaison/{id}", name="backmaisonshow" ,methods={"GET","POST"})
     */
    public function showmaison($id, Request $request)
    {



        $maison = $this->getDoctrine()->getRepository(MaisonHote::class)->find($id);







        return $this->render('maison_hote/maisonbackshow.html.twig', [
            'maison' => $maison

        ]);
    }
    /**
     * @IsGranted("ROLE_GERANT_MAISON_HOTE","ROLE_ADMIN")
     * @Route("/admin/maison_hote/new", name="new_maison_hote")
     * Method({"GET","POST"})
     */
    public function new(Request $request): Response
    {

        $maison = new MaisonHote();
        $form = $this->createForm(MaisonHoteType::class, $maison)
            ->add('save', SubmitType::class, [
                'label' => 'Create',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                $image->move(
                    $this->getParameter('images_direcoty'),
                    $fichier,
                );

                $img = new MaisonImages();
                $img->setName($fichier);
                $maison->addImage($img);
            }

            $image360=$form->get('image_360')->getData();
            if($image360)
            {
                /** @var UploadedFile $uploadedFile */
                $date= new DateTime('now');
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
                $originalFilename = pathinfo($image360->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename =  $originalFilename.'-'.$date->format('mmddyyyHHiiSS').'.'.$image360->guessExtension();
                $image360->move(
                    $destination,
                    $newFilename
                );
                $maison->setImage360($newFilename);
            }





            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($maison);
            $entityManager->flush();

            return $this->redirectToRoute('maison_hote_list');
        }

        return $this->render('maison_hote/new.html.twig', [
            'maison' => $maison,
            'form' => $form->createView()
        ]);
    }




    /**
     * @Route("/maison_hote/{id}", name="maison_hote_show")
     */

    public function show($id, Request $request, ContactNotification $notification)
    {
        $contact = new Contact();


        $maison = $this->getDoctrine()->getRepository(MaisonHote::class)->find($id);

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $notification->notify($contact);
            $this->addFlash('success', 'votre email a ete envoyer');
            return $this->redirectToRoute('maison_hote_list');
        }


        return $this->render('maison_hote/show.html.twig', [
            'maison' => $maison,
            'form' => $form->createView()
        ]);
    }

    /**
     *  @Route("/maison_hote/{id}/edit", name="edit_maison_hote") , methode={"GET","POST"}  
     */
    public function edit(Request $request, MaisonHote $maison)
    {
        $form = $this->createForm(MaisonHoteType::class, $maison)
            ->add('save new edit', SubmitType::class, [
                'label' => 'Update',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ]);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                $image->move(
                    $this->getParameter('images_direcoty'),
                    $fichier
                );

                $img = new MaisonImages();
                $img->setName($fichier);
                $maison->addImage($img);
            }
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->flush();

            return $this->redirectToRoute('maison_hote_list');
        }

        return $this->render('maison_hote/edit.html.twig', [
            'maison' => $maison,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/maison_hote/{id}/delete", name="maison_hote_delete")
     */
    public function delete(MaisonHote $maison, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($maison);
        $entityManager->flush();
        return $this->redirectToRoute('maison_hote_list');
    }

    /**
     * @Route("/maison_hote/supprime/image/{id}" , name="maison_hote_delete_image", methods={"DELETE"})
     */
    public function deleteImage(MaisonImages $image, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            // On récupère le nom de l'image
            $nom = $image->getName();
            // On supprime le fichier
            unlink($this->getParameter('images_directory') . '/' . $nom);

            // On supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }


        /**
         * @Route ("/tour/{id}",name="showTour")
         */
            public function tour($id,MaisonHoteRepository $repo)
        {
            $maison=$repo->find($id);
            return $this->render('maison_hote/tour.html.twig',['maison'=>$maison]);

        }



    }
