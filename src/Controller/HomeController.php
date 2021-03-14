<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Service\TodoService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class HomeController extends AbstractController
{
    public function index()
    {
        $name = 'Kiko';
        return $this->render('Home/index.html.twig', [
            'name' => $name
        ]);
    }

    public function getAllTodos(TodoService $todoService): JsonResponse
    {
        $todos = $todoService->getAllTodos();

        return $todos;
    }

    public function getOneTodo(Request $request, TodoService $todoService): JsonResponse
    {
        $id = $request->get('id');
        $todo = $todoService->getOneTodo($id);

        return $todo;
    }

    public function createTodo(Request $request, TodoService $todoService): JsonResponse
    {
        $body = json_decode($request->getContent());

        $todo = $todoService->createTodo($body);

        return $todo;
    }

    public function deleteTodo(Request $request, TodoService $todoService): JsonResponse
    {
        $id = $request->get('id');

        $todo = $todoService->deleteTodo($id);

        return $todo;
    }

    public function updateTodo(Request $request, TodoService $todoService): JsonResponse
    {
        $id = $request->get('id');
        $body = json_decode($request->getContent());

        $todo = $todoService->updateTodo($id, $body);

        return $todo;
    }
}