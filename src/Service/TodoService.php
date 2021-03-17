<?php

namespace App\Service;

use App\Entity\Todo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class TodoService
{
    private $entityManager;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    public function getAllTodos(): JsonResponse
    {
        $todos = $this->entityManager->getRepository(Todo::class)->findAll();
        $data = $this->serializer->serialize($todos, JsonEncoder::FORMAT);
        
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    public function getOneTodo($id): JsonResponse
    {
        $todos = $this->entityManager->getRepository(Todo::class)->find($id);
        if (!$todos) {
            return new JsonResponse(array('message' => 'Todo not found'), Response::HTTP_BAD_REQUEST, [], false);
        }

        $data = $this->serializer->serialize($todos, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    public function createTodo($body): JsonResponse
    {
        $todo = new Todo();
        $todo->setTitle($body->title);
        $todo->setTodoOrder($body->todoOrder);
        $todo->setStatus(1);
        $this->entityManager->persist($todo);
        $this->entityManager->flush();

        $data = $this->serializer->serialize($todo, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_CREATED, [], true);
    }

    public function deleteTodo($id): JsonResponse
    {
        $todo = $this->entityManager->getRepository(Todo::class)->find($id);
        if (!$todo) {
            return new JsonResponse(array('message' => 'Todo not found'), Response::HTTP_BAD_REQUEST, [], false);
        }

        $this->entityManager->remove($todo);
        $this->entityManager->flush();

        $data = $this->serializer->serialize(['message' => 'deleted', 'title' => $todo->getTitle()], JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    public function updateTodo($id, $body): JsonResponse
    {
        $todo = $this->entityManager->getRepository(Todo::class)->find($id);
        if (!$todo) {
            return new JsonResponse(array('message' => 'Todo not found'), Response::HTTP_BAD_REQUEST, [], false);
        }

        if (isset($body->title)) {
            $todo->setTitle($body->title);
        }

        if (isset($body->todoOrder)) {
            $body->todoOrder = intval($body->todoOrder);
            $todo->setTodoOrder($body->todoOrder);
        }

        $this->entityManager->flush();

        $data = $this->serializer->serialize($todo, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    public function updateTodosOrder($body)
    {
        $todos = $this->entityManager->getRepository(Todo::class)->findAll();
        foreach ($todos as $todo) {
            foreach ($body as $bodyEl) {
                if ($todo->getId() == $bodyEl->id) {
                    $todo->setTodoOrder(intval($bodyEl->todoOrder));
                }  
            }
        }

        $this->entityManager->flush();
        $this->entityManager->clear();

        $data = $this->serializer->serialize($todos, JsonEncoder::FORMAT);
        
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
}