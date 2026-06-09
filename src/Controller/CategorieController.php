<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategorieController extends AbstractController
{
    #[Route('/api/categories', name: 'all_categorie', methods: ['GET'])]
    public function getAllCategories(CategorieRepository $cr): JsonResponse
    {
        $categories = $cr->findAll();
        $data = [];
        foreach ($categories as $categorie) {
            $data[]  = [
                'id' => $categorie->getId(),
                'nom' => $categorie->getNom(),
            ];
        }
        return $this->json($data);
    }

    #[Route('/api/categories', name: 'insert_categorie', methods: ['POST'])]
    public function insertCategorie(Request $request, EntityManagerInterface $emi): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $categorie = new Categorie();
        $categorie->setNom($data['nom']);
        $emi->persist($categorie);
        $emi->flush();
        return $this->json([
            'id' => $categorie->getId(),
            'nom' => $categorie->getNom(),
        ], Response::HTTP_CREATED);

    }

    #[Route('/api/categories/{id}', name: 'get_categorie_by_id', methods: ['GET'])]
    public function getCategorieById(CategorieRepository $cr, int $id): JsonResponse
    {
        $categorie = $cr->find($id);
        if (!$categorie) {
            return $this->json(['message' => 'Catégorie non trouvée'], Response::HTTP_NOT_FOUND);
        }
        return $this->json([
            'id' => $categorie->getId(),
            'nom' => $categorie->getNom(),
        ]);
    }

    #[Route('/api/categories/{id}', name: 'put_categorie_by_id', methods: ['PUT'])]
    public function putCategorieById(Request $request,CategorieRepository $cr,EntityManagerInterface $emi, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $categorie = $cr->find($id);
        if (!$categorie) {
            return $this->json(['message' => 'Catégorie non trouvée'], Response::HTTP_NOT_FOUND);
        }
        $categorie->setNom($data['nom']);
        $emi->persist($categorie);
        $emi->flush();
        return $this->json([
            'nom' => $categorie->getNom(),
        ]);
    }

    #[Route('/api/categories/{id}', name: 'delete_categorie_by_id', methods: ['DELETE'])]
    public function deleteCategorieById(CategorieRepository $cr,EntityManagerInterface $emi, int $id): JsonResponse
    {
        $categorie = $cr->find($id);
        if (!$categorie) {
            return $this->json(['message' => 'Catégorie non trouvée'], Response::HTTP_NOT_FOUND);
        }
        $emi->remove($categorie);
        $emi->flush();
        return $this->json(['message' => 'Catégorie supprimée'], Response::HTTP_OK);
    }


}
