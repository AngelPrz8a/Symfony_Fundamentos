<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
class PageController extends AbstractController{

    /**
     * Especifica la ruta con la que llamara al metodo
     */
    #[Route("/",name:"home")]
    public function home(EntityManagerInterface $entityManager, Request $request):Response{

        /**
         * Crea al formulario y lo hace responsable del request
         */
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        /**
         * Si el formulario fue enviado y los datos son correctos
         * creara una persistencia de los datos
         * sincronizada la memoria con la BD
         * redireccionara
         */
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        /**
         * Cuando no se ha enviado el formulario
         * retornara la vista con
         * la lista de comentarios existentes ordenado DESC
         * creara la vista del formulario
         */
        return $this->render("home.html.twig", [
            //"comments" => $entityManager->getRepository(Comment::class)->findAll(),
            "comments" => $entityManager->getRepository(Comment::class)->findBy([],['id'=>'desc']),
            "form" => $form->createView(),
        ]);
    }
} 