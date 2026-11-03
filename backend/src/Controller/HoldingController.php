<?php

declare(strict_types=1);

namespace App\Controller;


use App\Entity\Holding;
use App\Dto\HoldingInput;
use App\Repository\HoldingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/holdings', format: 'json')]
final class HoldingController extends AbstractController
{
    #[Route('', name: 'api_holdings_list', methods: ['GET'])]
    public function list(HoldingRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll(), context: ['groups' => 'holding:read']);
    }

    #[Route('/{id}', name: 'api_holdings_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(#[MapEntity(id: 'id')] Holding $holding): JsonResponse
    {
        return $this->json($holding, context: ['groups' => 'holding:read']);
    }

    #[Route('', name: 'api_holdings_create', methods: ['POST'])]
    public function create(#[MapRequestPayload] HoldingInput $input, EntityManagerInterface $em): JsonResponse
    {
        $holding = new Holding();
        $holding
            ->setSymbol($input->symbol)
            ->setQuantity($input->quantity)
            ->setBoughtAt($input->boughtAt)
            ->setBuyPrice($input->buyPrice);
        $em->persist($holding);
        $em->flush();

        return $this->json($holding, 201, context: ['groups' => 'holding:read']);
    }

    #[Route('/{id}', name: 'api_holdings_update', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(#[MapRequestPayload] HoldingInput $input, #[MapEntity(id: 'id')] Holding $holding, EntityManagerInterface $em): JsonResponse 
    {
        $holding
            ->setSymbol($input->symbol)
            ->setQuantity($input->quantity)
            ->setBoughtAt($input->boughtAt)
            ->setBuyPrice($input->buyPrice);
        $em->flush();

        return $this->json($holding, 200, context: ['groups' => 'holding:read']);
    }

    #[Route('/{id}', name: 'api_holdings_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(#[MapEntity(id: 'id')] Holding $holding, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($holding);
        $em->flush();

        return new JsonResponse(null, 204);
    }
}