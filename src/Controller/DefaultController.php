<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * Page d'accueil
     * 
     * @Route("/", name="homepage", methods={"GET"})
     */
    public function index()
    {
        // On crée une tâche vide (pour ajout)
        $todo = new Todo();
        // On crée le formulaire via son FQCN et on "map" la tâche dessus
        $form = $this->createForm(TodoType::class, $todo);

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
