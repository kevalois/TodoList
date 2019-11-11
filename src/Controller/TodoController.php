<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use App\Model\TodoModel;
use App\Repository\TodoRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TodoController extends AbstractController
{
    /**
     * Liste des tâches
     * Attention cette action gère l'affichage et le traitement du form
     *
     * @Route("/todos", name="todo_list", methods={"GET", "POST"})
     */
    public function todoList(TodoRepository $todoRepository, Request $request)
    {
        //$todos = TodoModel::findAll();
        $todos = $todoRepository->findAll();

        // On crée une tâche vide (pour ajout)
        $todo = new Todo();
        // On crée le formulaire via son FQCN et on "map" la tâche dessus
        $form = $this->createForm(TodoType::class, $todo);

        // Le form prend en charge la requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On sauve en BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($todo);
            $entityManager->flush();

            $this->addFlash('success', 'Tâche ajoutée.');
            // On redirige vers la liste des tâches
            return $this->redirectToRoute('todo_list');
        }

        return $this->render('todo/list.html.twig', [
            'todos' => $todos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Affichage d'une tâche
     *
     * @Route("/todo/{id}", name="todo_show", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function todoShow($id)
    {
        // On va chercher la tâche
        $todo = TodoModel::find($id);

        // Si non existante => 404
        if (!$todo) {
            throw $this->createNotFoundException('Tâche non trouvée.');
        }

        return $this->render('todo/single.html.twig', [
            'todo' => $todo
        ]);
    }

    /**
     * Changement de statut
     *
     * @Route("/todo/{id}/{status}", name="todo_set_status", requirements={"id" = "\d+", "status"="done|undone"}, methods={"GET"})
     */
    public function todoSetStatus($id, $status)
    {
        // On modifie le statut via TodoModel
        $success = TodoModel::setStatus($id, $status);

        // Si non existante => 404
        if (!$success) {
            throw $this->createNotFoundException('Tâche non trouvée.');
        }

        // On jaoute un Flash message
        $this->addFlash('success', 'Statut modifié.');
        // $this->addFlash('success', 'Yeah !');
        // $this->addFlash('warning', 'Attention derrière toi !');

        // On redirige vers la liste des tâches
        return $this->redirectToRoute('todo_list');
    }

    /**
     * Ajout d'une tâche
     *
     * @Route("/todo/add", name="todo_add", methods={"POST"})
     *
     * La route est définie en POST parce qu'on veut ajouter une ressource sur le serveur
     */
    public function todoAdd(Request $request)
    {
        // On veut récupérer $_POST['task']
        // On utilise trim() pour supprimer les espaces blancs
        $task = trim($request->request->get('task'));

        // Si tâche vide
        if (empty($task)) {
            // Flash message
            $this->addFlash('danger', 'Veuillez renseigner un intitulé de tâche.');
            // Redirection
            return $this->redirectToRoute('todo_list');
        }

        // On l'ajoute à nos tâches
        TodoModel::add($task);
        // On redirige vers la liste des tâches
        return $this->redirectToRoute('todo_list');
    }

    /**
     * Suppression tâche
     * 
     * @param int $id Task id
     * 
     * @Route("/todo/delete/{id}", name="todo_delete", methods={"POST"})
     */
    public function delete($id)
    {
        $success = TodoModel::delete($id);

        if (!$success) {
            throw $this->createNotFoundException('Tâche non trouvée.');
        }

        $this->addFlash('success', 'Tâche supprimée.');

        return $this->redirectToRoute('todo_list');
    }

    /**
     * @Route("/api/todo/delete", name="api_todo_delete", methods={"POST"})
     */
    public function apiTodoDeleteAction(Request $request)
    {
        // On récupère l'id de la tâche à supprimer depuis le champ id du formulaire
        $todoId = $request->request->get('id');
        if(!is_int((int) $todoId)) {
            // Notifier l'utilisateur de l'erreur
            $result = ['error' => true];
        }
        else {
            // supprimer un todo à partir de son $id
            $done = TodoModel::delete($todoId);
            // $done = true ou false
            // on inverse pour dire si erreur lors du delete ou non
            $result = ['error' => !$done, 'id' => $todoId];
        }
        // Renvoie le JSON approprié
        return $this->json($result);
    }

    /**
     * Reset des tâches (mode dev)
     * 
     * @Route("/todos/reset", name="todo_reset")
     */
    public function reset()
    {
        // Reset depuis TodoModel
        TodoModel::reset();
        // Flash message
        $this->addFlash('success', 'Tâches réinitialisées.'); 
        // Redirection
        return $this->redirectToRoute('todo_list');
    }
}
