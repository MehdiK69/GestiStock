<?php

namespace App\Controller;

use App\Entity\Mouvement;
use App\Repository\MouvementRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


final class MouvementController extends AbstractController
{
    #[Route('/api/mouvements', name: 'all_mouvements', methods: ['GET'])]
    public function getAllMouvements(MouvementRepository $mr): JsonResponse
    {
        $mouvements = $mr->findAll();
        if (empty($mouvements)) {
            return $this->json(['message' => 'Pas de mouvements trouvés'], Response::HTTP_NOT_FOUND);
        }
        $data = [];
        foreach ($mouvements as $mouvement) {
            $data[] = [
                'id' => $mouvement->getId(),
                'quantite' => $mouvement->getQuantite(),
                'type' => $mouvement->getType(),
                'date' => $mouvement->getDate(),
                'id_produit' => $mouvement->getProduit()->getId()
            ];
        }
        return $this->json($data, Response::HTTP_OK);

    }

    #[Route('/api/mouvements/{id}', name: 'one_mouvement', methods: ['GET'])]
    public function getOneMouvement(MouvementRepository $mr, int $id): JsonResponse
    {
        $mouvement = $mr->find($id);
        if (empty($mouvement)) {
            return $this->json(['message' => 'Mouvement inexistant'], Response::HTTP_NOT_FOUND);
        }
        $data = [];
        $data['id'] = $mouvement->getId();
        $data['quantite'] = $mouvement->getQuantite();
        $data['type'] = $mouvement->getType();
        $data['date'] = $mouvement->getDate();
        $data['id_produit'] = $mouvement->getProduit()->getId();
        $data['nom_produit'] = $mouvement->getProduit()->getNom();
        return $this->json($data, Response::HTTP_OK);

    }

    #[Route('/api/mouvements', name: 'insert_mouvement',methods: ['POST'])]
    public function insertMouvement(Request $request,ProduitRepository $pr,EntityManagerInterface $emi): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $produit = $pr->find($data['id_produit']);
        if (empty($produit)) {
            return $this->json(['message' => 'Produit inexistant'], Response::HTTP_NOT_FOUND);
        }
        $mouvement = new Mouvement();
        $mouvement->setType($data['type']);
        if($mouvement->getType()=='ajout'){
            $produit->setQuantite($produit->getQuantite()+$data['quantite']);
        }
        if($mouvement->getType()=='retrait'){
            $produit->setQuantite($produit->getQuantite()-$data['quantite']);
        }
        $mouvement->setQuantite($data['quantite']);
        $mouvement->setDate(new \DateTimeImmutable($data['date']));
        $mouvement->setProduit($produit);
        $emi->persist($mouvement);
        $emi->persist($produit);
        $emi->flush();
        return $this->json([
            'id' => $mouvement->getId(),
            'quantite' => $mouvement->getQuantite(),
            'type' => $mouvement->getType(),
            'date' => $mouvement->getDate(),
            'id_produit' => $mouvement->getProduit()->getId(),
            'nom_produit' => $mouvement->getProduit()->getNom()
        ],Response::HTTP_CREATED);
    }
    #[Route('/api/mouvements/{id}', name: 'update_mouvement',methods: ['PUT'])]
    public function modifyMouvement(Request $request,MouvementRepository $mr,ProduitRepository $pr,EntityManagerInterface $emi,int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $mouvement = $mr->find($id);
        if (!$mouvement) {
            return $this->json(['message' => 'Mouvement non trouvé'], Response::HTTP_NOT_FOUND);
        }
        $ancienType = $mouvement->getType();
        $ancienneQuantite = $mouvement->getQuantite();
        $produit = $pr->find($data['id_produit']);
        if (empty($produit)) {
            return $this->json(['message' => 'Produit inexistant'], Response::HTTP_NOT_FOUND);
        }
        $mouvement->setType($data['type']);
        if($ancienType=='ajout'){
            $produit->setQuantite($produit->getQuantite()-$ancienneQuantite);
        }
        if($ancienType=='retrait'){
            $produit->setQuantite($produit->getQuantite()+$ancienneQuantite);
        }
        if($mouvement->getType()=='ajout'){
            $produit->setQuantite($produit->getQuantite()+$data['quantite']);
        }
        if($mouvement->getType()=='retrait'){
            $produit->setQuantite($produit->getQuantite()-$data['quantite']);
        }
        $mouvement->setQuantite($data['quantite']);
        $mouvement->setDate(new \DateTimeImmutable($data['date']));
        $mouvement->setProduit($produit);
        $emi->persist($mouvement);
        $emi->persist($produit);
        $emi->flush();
        return $this->json([
            'id' => $mouvement->getId(),
            'type' => $mouvement->getType(),
            'quantite' => $mouvement->getQuantite(),
            'date' => $mouvement->getDate(),
            'id_produit' => $mouvement->getProduit()->getId(),
            'nom_produit' => $mouvement->getProduit()->getNom()
        ]);
    }


}
