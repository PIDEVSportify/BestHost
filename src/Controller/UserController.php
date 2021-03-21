<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Repository\UserRepository;
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

        return $this->render('user/profil.html.twig',['form'=>$form->createView(),'password_form'=>$password_form->createView()]);
    }

   /**
    * @Route("/profile/update", name="update_profile")
    */
   public function update_profile(Request $request,UserRepository $repo)
   {

   }



}
