<?php

namespace App\Controller;

use App\Entity\Camping;
use App\Entity\Offre;
use App\Entity\Urlizer;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Dompdf\Dompdf;
use Dompdf\Options;
use phpDocumentor\Reflection\Types\Null_;
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
        $liste=array(array('site'=>$site));
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
            ->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'ExampleCaptcha'
            ))
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
            if($find)
                $camping->setOffreId($find);

            else
                $camping->setOffreId(Null);
            $latitude= substr($camping->getLocalisationCamping(),0, strpos($camping->getLocalisationCamping(), ","));
            $longitude= substr($camping->getLocalisationCamping(),strpos($camping->getLocalisationCamping(), ",")+2);
            $camping->setLongitudeCamping($longitude);
            $camping->setLatitudeCamping($latitude);
            $camping->setImageCamping($newFilename);
            $entityManager->persist($camping);
            $entityManager->flush();
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
        if($request1->request->get('Id_offre'))
        $camping->setFindOffre($camping->getOffreId()->getId());
        else
            $camping->setFindOffre(0);
        $form = $this->createFormBuilder($camping)
            ->add('id', NumberType::class)
            ->add('localisation_camping', TextType::class)
            ->add('description_camping', TextType::class)
            ->add('type_camping', TextType::class)
            ->add('imageFile', FileType::class, [
                'mapped' => false
            ])
            ->add('find_offre', NumberType::class)
            ->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'ExampleCaptcha'
            ))
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
                $camping->setOffreId(Null);
                $entityManager->flush();
                $this->addFlash("warning", "No Offer found for id_offer");
                $this->redirectToRoute('Afficher_site');
            }
            $latitude= substr($camping->getLocalisationCamping(),0, strpos($camping->getLocalisationCamping(), ","));
            $longitude= substr($camping->getLocalisationCamping(),strpos($camping->getLocalisationCamping(), ",")+2);
            $camping->setLongitudeCamping($longitude);
            $camping->setLatitudeCamping($latitude);
            $camping->setOffreId($find);
            $entityManager->flush();
            $this->addFlash("success", "The site has been updated");
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

    /**
     * @Route("/front_sites", name="Afficher_front")
     */
    public function afficher_front(Request $request)
    {
        dump($request);
        $get_sites = $this->getDoctrine()->getRepository(Camping::class);
        $site = $get_sites->findAll();
        if($request->request->count()>0) {
            $min = $request->request->get('min_price');
            $max = $request->request->get('max_price');
            $search = $this->getDoctrine()->getRepository(Camping::class)->sql($request);
            dump($search);
            return $this->render('camping/filtre_sites.html.twig',
                ['filtre' => array(array('min'=>$min),array('max'=>$max),array('search'=>$search))]);
        }
        return $this->render('camping/index.html.twig',
            ['liste' => $site]);
    }

    /**
     * @Route("/rate_site",name="rating_camping")
     */
    function rating_camping(Request $request)
    {
        if ($request->request->count()>0) {
            $rate = $request->request->get('rating');
            $entityManager = $this->getDoctrine()->getManager();
            $get_sites = $entityManager->getRepository(Camping::class);
            $site = $get_sites->find($request->request->get('id_rate'));
            $site->setRatingCamping($site->getRatingCamping()+1);
            if(!$site->getAverageRating())
                $site->setAverageRating($rate);
            else
                $site->setAverageRating(($site->getAverageRating()+$rate));
            $entityManager->flush();

            return $this->redirectToRoute("Afficher_front");
        }
        throw $this->createNotFoundException(
            'error' . $request->request->get('id_rate')
        );
    }

    /**
     * @Route("/generate_pdf",name="generate")
     */
    public function generate_pdf()
    {
        $get_sites=$this->getDoctrine()->getRepository(Camping::class);
        $site=$get_sites->findAll();
        $liste=array(array('site'=>$site),array('offre'=>$this->getDoctrine()->getRepository(Offre::class)->findAll()));

        $Options = new Options();
        $Options->set('isRemoteEnabled',true);
        $Options->set('defaultFont', 'Arial');

        $pdf = new Dompdf($Options);
        $html = $this->renderView('camping/pdf_sites.html.twig', [
            'liste' => $liste
        ]);

        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        $pdf->stream("speaky.pdf", [
            "Attachment" => true
        ]);
    }

}