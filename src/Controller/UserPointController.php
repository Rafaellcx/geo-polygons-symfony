<?php

namespace App\Controller;

use App\Entity\UserPoint;
use App\Http\Helpers\JsonFormat;
use App\Repository\UserPointRepository;
use App\Service\UserPointService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class UserPointController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPointService $userPointService;
    private UserPointRepository $userPointRepository;

    /**
     * @param UserPointRepository $userPointRepository
     * @param EntityManagerInterface $entityManager
     * @param UserPointService $userPointService
     */
    public function __construct(
        UserPointRepository $userPointRepository,
        EntityManagerInterface $entityManager,
        UserPointService $userPointService
    )
    {
        $this->userPointRepository = $userPointRepository;
        $this->entityManager = $entityManager;
        $this->userPointService = $userPointService;
    }

    #[\Symfony\Component\Routing\Annotation\Route('/user-point/all', name: 'all', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $userPoints = $this->entityManager->getRepository(UserPoint::class)->findAll();

        return $this->json([
            'data' => $userPoints
        ],200,
            [],
            [AbstractNormalizer::ATTRIBUTES => ['id','latitude','longitude','geom','createdAt','updatedAt', 'municipal' => ['id','name','uf']]]
        );
    }

    #[Route('/user-point/find/{id}', name: 'find', methods: ['GET'])]
    public function find(int $id): JsonResponse
    {
        $userPoint = $this->userPointRepository->find($id);

        if(!$userPoint) return JsonFormat::error(message: 'Oops, User Point not found.', data: [],code: 404);

        return $this->json([
            'data' => $userPoint
        ],200,
            [],
            [AbstractNormalizer::ATTRIBUTES => ['id','latitude','longitude','geom','createdAt','updatedAt', 'municipal' => ['id','name','uf']]]
        );
    }

    #[Route('/user-point/store', name: 'store',methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        return $this->userPointService->store(json_decode($request->getContent(), true));
    }

    /**
     * @throws Exception
     */
    #[Route('/user-point/update/{id}', name: 'update',methods: ['PUT','PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        return $this->userPointService->update(id: $id,data: json_decode($request->getContent(), true));
    }

    #[Route('/user-point/delete/{id}', name: 'delete',methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return $this->userPointService->delete($id);
    }
}
