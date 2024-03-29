<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Form\InscriptionType;
use App\Repository\UserRepository;
use DateTime;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Omines\DataTablesBundle\Column\TextColumn;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("/admin",name="admin_")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard()
    {
        return $this->render('dashboard/index.html.twig');
    }


    /**
     * @param Request $request
     * @param DataTableFactory $dataTableFactory
     * @return Response
     * @Route("/showUsers",name="showUsers")
     */

    public function showAction(Request $request, DataTableFactory $dataTableFactory, UserRepository $repo)
    {
        $users = $repo->findAll();

        /*  $table = $dataTableFactory->create()
              ->add('email', TextColumn::class, ['label'=>'Email','searchable'=> true])
              ->add('first_name',TextColumn::class,['label'=>'Nom'])
              ->add('last_name',TextColumn::class,['label'=>'Prenom'])
              ->add('cin', TextColumn::class, ['label'=>'CIN'])
              ->add('created_at', DateTimeColumn::class, ['label'=>'Created_at','format'=>
              'd-m-Y  H:m:s'])
              ->add('avatar',TextColumn::class,['label'=>'Avatar','orderable'=>false,'render'=>
          function($value)
          {
              return "<img src=\"".$value."\"/ width=\"60px\" height=\"60px\">";
          }])
          ->add('delete',TextColumn::class, ['label'=>'Action','orderable'=>false, 'field'=>'user.email','render'=>
                  function ($value)
                  {
                      if ($this->getUser()->getUsername()==$value)
                          return "";
                      $btn= "<form method=\"post\" action=\"".$this->generateUrl('admin_deleteUser')."\">
                      <button class='btn_3' type='submit' name='supprimer' value='".$value."'> Supprimer</button>
                  </form>";

                      return $btn;
                  }])
              ->add('modify',TextColumn::class, ['label'=>'', 'orderable'=>false,'field'=>'user.id','render'=>
                  function ($value)
                  {
                      if ($this->getUser()->getUsername()==$value)
                          return "";
                      $btn= "<form method=\"post\" action=\"".$this->generateUrl('admin_modifyUser')."\">
                      <button class='btn btn-sm btn-outline-warning' type='submit' name='modifier' value='".$value."'> Modifier</button>
                  </form>";

                      return $btn;
                  }])
              ->createAdapter( ORMAdapter::class ,[
                  'entity'=>User::class])

              ->handleRequest($request);


          if ($table->isCallback()) {
              return $table->getResponse();
          }

          return $this->render('admin/showUsers.html.twig', ['datatable' => $table]);*/


        return $this->render("admin/showUsers.html.twig", ['users' => $users]);


    }


    /**
     * @Route("/deleteUser",name="deleteUser")
     */

    public function deleteUser(Request $request, UserRepository $repo)
    {

        $user = $repo->findOneBy(['email' => $request->get('supprimer')]);

        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès');
        } catch (\Exception $e) {
            $this->addFlash('error', $e . getMessage());
        } finally {
            return $this->redirectToRoute("admin_showUsers");
        }


    }


    /**
     * @Route("/modifyUser/{id}",name="modifyUser")
     */
    public function modifyUser(Request $request, UserRepository $repo, UserPasswordEncoderInterface $encoder, $id)
    {

        $user = $repo->find($id);

        $form = $this->createFormBuilder($user)
            ->add('first_name', TextType::class)
            ->add('last_name', TextType::class)
            ->add('email', EmailType::class)
            ->add('cin', TextType::class)
            ->add('submit', SubmitType::class)->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Paramètres mis à jour");
            return $this->redirectToRoute('admin_modifyUser', ['id' => $id,'user'=>$user]);
        }


        $password_form = $this->createForm(ChangePasswordFormType::class, $user);
        $password_form->handleRequest($request);

        if ($password_form->isSubmitted() && $password_form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Mot de passe modifié avec succès");
            return $this->redirectToRoute('admin_modifyUser', ['id' => $id,'user'=>$user]);
        }

        if ( $password_form->isSubmitted() && !$password_form->isValid())
        {
            $this->addFlash('error',"Vérifier les champs du mot de passe");
        }

        return $this->render('admin/modifyUser.html.twig', ['form' => $form->createView(),
            'password_form' => $password_form->createView(),'user'=>$user]);
    }

    /**
     * @param Request $request
     * @param UserRepository $repo
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/banUser",name="banUser")
     */
    public function banUser(Request $request, UserRepository $repo)
    {

        $user = $repo->findOneBy(['email' => $request->get('ban')]);

        $user->ban();
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', "utilisateur banni");
        return $this->redirectToRoute('admin_showUsers');

    }

    /**
     * @param Request $request
     * @param UserRepository $repo
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/unbanUser",name="unbanUser")
     */
    public function unbanUser(Request $request, UserRepository $repo)
    {

        $user = $repo->find($request->get('unban'));
        $user->unban();
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', "utilisateur n'est plus banni");
        return $this->redirectToRoute('admin_showUsers');

    }


    /**
     * @Route("/facebook/unlink", name="facebook_unlink")
     */
    public function facebookUnlink(Request $request, UserRepository $repo)
    {


        $user = $repo->findOneBy(['email' => $request->get('email')]);
        $user->setFacebookId(null);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', "modification effectuée");
        return $this->redirectToRoute('admin_modifyUser', ['id' => $user->getId(),'user'=>$user]);

    }

    /**
     * @route("/google/unlink",name="google_unlink")
     */

    public function googleUnlink(Request $request, UserRepository $repo)
    {
        $user = $repo->findOneBy(['email' => $request->get('email')]);
        $user->setGoogleId(null);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', "modification effectuée");
        return $this->redirectToRoute('admin_modifyUser', ['id' => $user->getId(),'user'=>$user]);

    }

    /**
     * @Route("/avatar",name="update_avatar")
     */
    public function updateAvatar(Request $request,UserRepository $repo)
    {


        $filesystem = new Filesystem();

        $user=$repo->findOneBy(['email'=>$request->get('avatar_submit')]);
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
        return $this->redirectToRoute('admin_modifyUser',['id' => $user->getId(),'user'=>$user]);
    }




}
