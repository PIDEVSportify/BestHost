<?php

namespace App\Controller;
use App\Entity\Post;
use App\Entity\Category;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ForumController extends AbstractController
{
 /**
     * @Route("/forum", name="forum")
     */
    public function index(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Category::class);
        $repos = $this->getDoctrine()->getRepository(Post::class);
        
        $Category = $repo->findAll();
$Post = $repos->findAll();
        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
            'category' => $Category,
            'Post' => $Post
            ]);
    }
   /**
     * @Route("/forum/new", name="forum_create")
     * @Route("/forum/{id}/edit", name="forum_edit")
     */
    public function form(Post $Post=null,Request $request){
if(!$Post){
    $Post = new Post();
}
       
              $form = $this->createFormBuilder($Post)
              ->add('thread',TextType::class,['attr' => [
                'placeholder'=>"Thread Name",
                'class' => 'form-control' 
                              ]  
                              ]) 
              ->add('user',TextType::class,['attr' => [
                'placeholder'=>"User Name",
                            'class' => 'form-control'  ]  
                              ])                                   
              ->add('title',TextType::class,['attr' => [
'placeholder'=>"Post Title",
'class' => 'form-control' 
              ]  
              ])                                     
              ->add('content',TextareaType::class,['attr' => [
                'placeholder'=>"Post Content",
                'class' => 'form-control' 
                              ]  
                              ])                                                                
              ->add('date'
                              )->getForm();  
                              $form->handleRequest($request);
                              
        if ($form->isSubmitted() && $form->isValid()) {
                    
            $manager = $this->getDoctrine()->getManager();
                              $manager->persist($Post)      ;
                              $manager->flush($Post)      ;
        
                              return $this->redirectToRoute('forum');
                            }
        
        return $this->render('forum/create.html.twig',[                                           
                'formForum' => $form->createView(),
                'editMode' =>$Post->getId()!==null]);                      
    

}
/**
     * @Route("/forum/{id}/delete", name="forum_delete")
     */
    public function delete(Post $post,Request $request){
        $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
            return $this->redirectToRoute('forum');


    }

   
}