<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PrestataireType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 * @Route("/prestataire",name="prestataire_")
 */

class PrestataireController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function inscription( Request $request , UserPasswordEncoderInterface  $encoder ): Response
    {
        $user= new User();
        $form=$this->createForm(PrestataireType::class,$user)
                    ->add('S\'inscrire',SubmitType::class,['attr'=>
        ['class'=>'btn_3']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {

            $filesystem = new Filesystem();
            $filename= json_decode($request->get('avatar'))->name;


            /** @var UploadedFile $uploadedFile */

            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($filename, PATHINFO_FILENAME);
            $extension=pathinfo($filename,PATHINFO_EXTENSION);

            $date= new DateTime('now');
            $newFilename = $originalFilename.$date->format('mmddyyyHHiiSS').'.'.$extension;
            $data=base64_decode(json_decode($request->get('avatar'))->data);

            $filesystem->dumpFile($destination."/".$newFilename,$data);
            $user->setAvatar("uploads/".$newFilename);
            $em= $this->getDoctrine()->getManager();
            $user->setPassword($encoder->encodePassword($user,$user->getPassword()));
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('login');
        }
        
        return $this->render('prestataire/inscription.html.twig',['form'=>$form->createView()]);


    }
}
