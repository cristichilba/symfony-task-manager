<?php
/**
 * Created by PhpStorm.
 * User: Chilba Cristian
 * Date: 7/18/2018
 * Time: 10:40 PM
 */
namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/task")
 */
class TaskController extends Controller
{
    /**
     * @Route("/", name="task_index", methods="GET")
     *
     * @return Response
     */
    public function index(): Response
    {
        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findBy([], ['dueDate' => 'ASC']);

        $past = array_filter($tasks, function (Task $task) use ($tasks) {
            $interval = (new \DateTime())->diff($task->getDueDate());

            return ($interval->invert === 1 && $interval->days >= 1);
        });

        $today = array_filter($tasks, function (Task $task) use ($tasks) {
            $interval = (new \DateTime())->diff($task->getDueDate());

            return ($interval->days === 0);
        });

        $thisWeek = array_filter($tasks, function (Task $task) use ($tasks) {
            $interval = (new \DateTime())->diff($task->getDueDate());

            return ($interval->days > 0 && $interval->days <= 7);
        });

        $future = array_filter($tasks, function (Task $task) use ($tasks) {
            $interval = (new \DateTime())->diff($task->getDueDate());

            return ($interval->invert === 0 && $interval->days > 7);
        });

        return $this->render('task/index.html.twig', [
            'past' => $past,
            'today' => $today,
            'this_week' => $thisWeek,
            'future' => $future,
        ]);
    }

    /**
     * @Route("/new", name="task_new", methods="GET|POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_show", methods="GET")
     *
     * @param Task $task
     *
     * @return Response
     */
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', ['task' => $task]);
    }

    /**
     * @Route("/{id}/edit", name="task_edit", methods="GET|POST")
     *
     * @param Request $request
     * @param Task    $task
     *
     * @return Response
     */
    public function edit(Request $request, Task $task): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('task_index', ['id' => $task->getId()]);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="task_delete", methods="DELETE")
     *
     * @param Request $request
     * @param Task    $task
     *
     * @return Response
     */
    public function delete(Request $request, Task $task): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
        }

        return $this->redirectToRoute('task_index');
    }

    /**
     * @Route("/{id}/status", name="task_status", methods="POST")
     *
     * @param Request $request
     * @param Task    $task
     *
     * @return Response
     */
    public function toggleStatus(Request $request, Task $task): Response
    {
        $value = $request->request->get('value');
        $status = ($value === 'true') ? 'inactive' : 'active';
        $task->setStatus($status);
        $this->getDoctrine()->getManager()->persist($task);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse($status);
    }
}
