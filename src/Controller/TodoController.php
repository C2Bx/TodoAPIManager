<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TodoController extends AbstractController
{
    // Route pour récupérer la liste des todos, méthode GET
    #[Route('/todos', name: 'get_todos', methods: ['GET'])]
    public function getTodos(TodoRepository $todoRepository, SerializerInterface $serializer): JsonResponse
    {
        // Récupère tous les todos depuis la base de données
        $todos = $todoRepository->findAll();

        // Sérialise les todos en format JSON
        $jsonTodos = $serializer->serialize($todos, 'json', ['groups' => 'todo:read']);
        
        // Retourne les todos en réponse JSON avec un code 200 (OK)
        return new JsonResponse($jsonTodos, Response::HTTP_OK, [], true);
    }

    // Route pour créer un nouveau todo, méthode POST
    #[Route('/todos', name: 'create_todo', methods: ['POST'])]
    public function createTodo(Request $request, EntityManagerInterface $em, ValidatorInterface $validator, SerializerInterface $serializer): JsonResponse
    {
        // Décode les données JSON envoyées dans la requête
        $data = json_decode($request->getContent(), true);

        // Crée une nouvelle instance de Todo et initialise ses propriétés
        $todo = new Todo();
        $todo->setTitle($data['title'] ?? ''); // Définit le titre du todo
        $todo->setDescriptionLongue($data['descriptionLongue'] ?? null); // Définit la description longue du todo
        $todo->setResume($data['resume'] ?? substr($data['descriptionLongue'] ?? '', 0, 100)); // Définit un résumé
        $todo->setDueAt(isset($data['dueAt']) ? new \DateTimeImmutable($data['dueAt']) : null); // Définit la date d'échéance
        $todo->setDone(false); // Initialise le todo comme non terminé
        $todo->setCreatedAt(new \DateTimeImmutable()); // Définit la date de création
        $todo->setUpdatedAt(new \DateTimeImmutable()); // Définit la date de mise à jour

        // Valide les données du todo
        $errors = $validator->validate($todo);
        if (count($errors) > 0) {
            // Si des erreurs sont présentes, les retourne dans une réponse JSON avec un code 400 (Bad Request)
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['error' => implode(', ', $errorMessages)], Response::HTTP_BAD_REQUEST);
        }

        // Persiste l'objet todo dans la base de données
        $em->persist($todo);
        $em->flush();

        // Sérialise le todo créé en format JSON
        $jsonTodo = $serializer->serialize($todo, 'json', ['groups' => 'todo:read']);
        
        // Retourne une réponse JSON avec le todo créé et un code 201 (Created)
        return new JsonResponse(['status' => 'Todo créé avec succès', 'todo' => json_decode($jsonTodo)], Response::HTTP_CREATED);
    }

    // Route pour supprimer un todo spécifique, méthode DELETE
    #[Route('/todos/{id}', name: 'delete_todo', methods: ['DELETE'])]
    public function deleteTodo(int $id, TodoRepository $todoRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // Recherche le todo par son ID
        $todo = $todoRepository->find($id);
        if (!$todo) {
            // Si le todo n'est pas trouvé, retourne une réponse JSON avec un code 404 (Not Found)
            return new JsonResponse(['error' => 'Le todo demandé est introuvable.'], Response::HTTP_NOT_FOUND);
        }

        // Supprime le todo de la base de données
        $entityManager->remove($todo);
        $entityManager->flush();

        // Retourne une réponse JSON confirmant la suppression avec un code 200 (OK)
        return new JsonResponse(['status' => 'Todo supprimé avec succès'], Response::HTTP_OK);
    }

    // Route pour mettre à jour un todo spécifique, méthode PUT ou PATCH
    #[Route('/todos/{id}', name: 'update_todo', methods: ['PUT', 'PATCH'])]
    public function updateTodo(Request $request, int $id, TodoRepository $todoRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer): JsonResponse
    {
        // Recherche le todo par son ID
        $todo = $todoRepository->find($id);
        if (!$todo) {
            // Si le todo n'est pas trouvé, retourne une réponse JSON avec un code 404 (Not Found)
            return new JsonResponse(['error' => 'Le todo demandé est introuvable.'], Response::HTTP_NOT_FOUND);
        }

        // Décode les données JSON envoyées dans la requête
        $data = json_decode($request->getContent(), true);

        // Met à jour les propriétés du todo si elles sont présentes dans les données
        if (isset($data['title'])) {
            $todo->setTitle($data['title']);
        }
        if (isset($data['descriptionLongue'])) {
            $todo->setDescriptionLongue($data['descriptionLongue']);
        }
        if (isset($data['done'])) {
            $todo->setDone($data['done']);
        }
        if (isset($data['dueAt'])) {
            $todo->setDueAt(new \DateTimeImmutable($data['dueAt']));
        }

        // Met à jour la date de mise à jour du todo
        $todo->setUpdatedAt(new \DateTimeImmutable());

        // Valide les nouvelles données du todo
        $errors = $validator->validate($todo);
        if (count($errors) > 0) {
            // Si des erreurs sont présentes, les retourne dans une réponse JSON avec un code 400 (Bad Request)
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['error' => implode(', ', $errorMessages)], Response::HTTP_BAD_REQUEST);
        }

        // Enregistre les modifications dans la base de données
        $entityManager->flush();

        // Sérialise le todo mis à jour en format JSON
        $jsonTodo = $serializer->serialize($todo, 'json', ['groups' => 'todo:read']);
        
        // Retourne une réponse JSON avec le todo mis à jour et un code 200 (OK)
        return new JsonResponse(json_decode($jsonTodo), Response::HTTP_OK);
    }

    // Route pour filtrer les todos par critères, méthode GET
    #[Route('/todos/filter', name: 'filter_todos', methods: ['GET'])]
    public function filterTodos(Request $request, TodoRepository $todoRepository, SerializerInterface $serializer): JsonResponse
    {
        // Récupère les paramètres de recherche et de tri depuis la requête
        $search = $request->query->get('search');
        $sort = $request->query->get('sort', 'dueAt');

        // Crée une requête pour filtrer les todos en fonction des critères
        $queryBuilder = $todoRepository->createQueryBuilder('t');

        if ($search) {
            // Ajoute une condition de recherche si le paramètre est présent
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('t.title', ':search'),
                    $queryBuilder->expr()->like('t.descriptionLongue', ':search')
                )
            )
            ->setParameter('search', '%' . $search . '%');
        }

        // Tri les résultats selon le critère spécifié
        $queryBuilder->orderBy('t.' . $sort, 'ASC');

        // Exécute la requête et récupère les résultats
        $todos = $queryBuilder->getQuery()->getResult();

        // Sérialise les todos filtrés en format JSON
        $jsonTodos = $serializer->serialize($todos, 'json', ['groups' => 'todo:read']);
        
        // Retourne les todos filtrés en réponse JSON avec un code 200 (OK)
        return new JsonResponse($jsonTodos, Response::HTTP_OK, [], true);
    }
}
