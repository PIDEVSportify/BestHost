<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping\Driver\DatabaseDriver;
use PhpParser\Node\Scalar\MagicConst\File;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use  Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/user",name="user_")
 * @IsGranted ("IS_AUTHENTICATED_FULLY")
 */

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function profile(Request $request,UserPasswordEncoderInterface $encoder,UserRepository $repo): Response
    {

        $user= $repo->find($this->getUser()) ;

        $form=$this->createFormBuilder($user)
            ->add('first_name',TextType::class)
            ->add('last_name',TextType::class)
            ->add('email',EmailType::class)
            ->add('cin',TextType::class)
            ->add('submit',SubmitType::class)->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success',"Paramètres mis à jour");
            return $this->redirectToRoute('user_profile');
        }


        $password_form=$this->createForm(ChangePasswordFormType::class,$user);
        $password_form->handleRequest($request);

        if($password_form->isSubmitted() && $password_form->isValid())
        {
            $hash=$encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success',"Mot de passe modifié avec succès");
            return $this->redirectToRoute('user_profile');
        }
        if ( $password_form->isSubmitted() && !$password_form->isValid())
        {
            $this->addFlash('error',"Vérifier les champs du mot de passe");
        }


        return $this->render('user/profil.html.twig',['form'=>$form->createView(),'password_form'=>$password_form->createView()]);
    }

   /**
    * @Route("/facebook/unlink", name="facebook_unlink")
    */
   public function facebookUnlink(Request $request,UserRepository $repo)
   {


    $user=$repo->findOneBy(['email'=>$request->get('email')]);
    $user->setFacebookId(null);
    $this->getDoctrine()->getManager()->flush();
    $this->addFlash('success',"modification effectuée");
       return $this->redirectToRoute('user_profile');

   }

   /**
    * @route("/google/unlink",name="google_unlink")
    */

   public function googleUnlink(Request $request ,UserRepository $repo)
   {
       $user=$repo->findOneBy(['email'=>$request->get('email')]);
       $user->setGoogleId(null);
       $this->getDoctrine()->getManager()->flush();
       $this->addFlash('success',"modification effectuée");
       return $this->redirectToRoute('user_profile');

   }

   /**
    * @Route("/avatar",name="update_avatar")
    */
   public function updateAvatar(Request $request,UserRepository $repo)
   {

       $filesystem = new Filesystem();

       $user=$repo->find($this->getUser());
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
      $this->getDoctrine()->getManager()->flush();

      $this->addFlash('success',"Avatar modifié avec succès");
    return $this->redirectToRoute('user_profile');
   }



}
