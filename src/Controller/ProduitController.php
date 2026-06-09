<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProduitController extends AbstractController
{
    #[Route('/api/produits', name: 'all_produit',methods: ['GET'])]
    public function getAllProduits(ProduitRepository $pr): JsonResponse
    {
        $produits = $pr->findAll();
        if(empty($produits)){
            return $this->json(['message' => 'Produit non trouvée'], Response::HTTP_NOT_FOUND);
        }
        $data = [];
        foreach ($produits as $produit) {
            $data[] = [
                'id' => $produit->getId(),
                'nom' => $produit->getNom(),
                'prix' => $produit->getPrix(),
                'quantite' => $produit->getQuantite(),
                'seuil_alerte' => $produit->getSeuilAlerte(),
                'id_categorie' => $produit->getCategorie()->getId(),
                'nom_categorie' => $produit->getCategorie()->getNom()
            ];
        }
        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/api/produits/{id}', name: 'get_produit_by_id',methods: ['GET'])]
    public function getlProduitbyId(ProduitRepository $pr,int $id): JsonResponse
    {
        $produit = $pr->find($id);
        if(empty($produit)){
            return $this->json(['message' => 'Produit non trouvée'], Response::HTTP_NOT_FOUND);
        }
        $data[] = [
            'id' => $produit->getId(),
            'nom' => $produit->getNom(),
            'prix' => $produit->getPrix(),
            'quantite' => $produit->getQuantite(),
            'seuil_alerte' => $produit->getSeuilAlerte(),
            'id_categorie' => $produit->getCategorie()->getId(),
            'nom_categorie' => $produit->getCategorie()->getNom()
        ];
        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/api/produits', name: 'insert_produit',methods: ['POST'])]
    public function insertProduit(Request $request,CategorieRepository $cr,EntityManagerInterface $emi): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $produit = new Produit();
        $produit->setNom($data['nom']);
        $produit->setPrix($data['prix']);
        $produit->setQuantite($data['quantite']);
        $produit->setSeuilAlerte($data['seuil_alerte']);
        $categorie = $cr->find($data['id_categorie']);
        if (!$categorie) {
            return $this->json(['message' => 'Catégorie non trouvée'], Response::HTTP_NOT_FOUND);
        }
        $produit->setCategorie($categorie);
        $emi->persist($produit);
        $emi->flush();
        return $this->json([
            'id' => $produit->getId(),
            'nom' => $produit->getNom(),
            'prix' => $produit->getPrix(),
            'quantite' => $produit->getQuantite(),
            'seuil_alerte' => $produit->getSeuilAlerte(),
            'id_categorie' => $produit->getCategorie()->getId(),
            'nom_categorie' => $produit->getCategorie()->getNom()
        ],Response::HTTP_CREATED);
    }

    #[Route('/api/produits/{id}', name: 'update_produit',methods: ['PUT'])]
    public function modifierProduit(Request $request,ProduitRepository $pr,CategorieRepository $cr,EntityManagerInterface $emi,int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $produit = $pr->find($id);
        if (!$produit) {
            return $this->json(['message' => 'Produit non trouvé'], Response::HTTP_NOT_FOUND);
        }
        $produit->setNom($data['nom']);
        $produit->setPrix($data['prix']);
        $produit->setQuantite($data['quantite']);
        $produit->setSeuilAlerte($data['seuil_alerte']);
        $categorie = $cr->find($data['id_categorie']);
        if (!$categorie) {
            return $this->json(['message' => 'Catégorie non trouvée'], Response::HTTP_NOT_FOUND);
        }
        $produit->setCategorie($categorie);
        $emi->persist($produit);
        $emi->flush();
        return $this->json([
            'id' => $produit->getId(),
            'nom' => $produit->getNom(),
            'prix' => $produit->getPrix(),
            'quantite' => $produit->getQuantite(),
            'seuil_alerte' => $produit->getSeuilAlerte(),
            'id_categorie' => $produit->getCategorie()->getId(),
            'nom_categorie' => $produit->getCategorie()->getNom()
        ]);
    }


}
