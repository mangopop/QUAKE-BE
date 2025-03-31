<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/categories', name: 'api_categories_')]
class CategoryController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): JsonResponse
    {
        $categories = $categoryRepository->findAll();
        return $this->json($categories);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'])) {
            return $this->json(['error' => 'Name is required'], 400);
        }

        $category = new Category();
        $category->setName($data['name']);

        $entityManager->persist($category);
        $entityManager->flush();

        return $this->json($category, 201);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Category $category): JsonResponse
    {
        return $this->json($category);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, Category $category, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'])) {
            return $this->json(['error' => 'Name is required'], 400);
        }

        $category->setName($data['name']);
        $entityManager->flush();

        return $this->json($category);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Category $category, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->json(null, 204);
    }
}