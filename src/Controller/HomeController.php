<?php

namespace App\Controller;

use App\Service\TodoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    public function index()
    {
        return $this->render('Pages/Home/index.html.twig');
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

    public function updateTodosOrder(Request $request, TodoService $todoService): JsonResponse
    {
        $body = json_decode($request->getContent());

        $todo = $todoService->updateTodosOrder($body);

        return $todo;
    }
}