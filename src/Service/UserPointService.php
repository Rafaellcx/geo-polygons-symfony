<?php

namespace App\Service;

use App\Entity\MunicipalGeometry;
use App\Entity\UserPoint;
use App\Http\Helpers\JsonFormat;
use App\Repository\UserPointRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserPointService
{
    private UserPointRepository $userPointRepository;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    /**
     * @param UserPointRepository $userPointRepository
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(
        UserPointRepository $userPointRepository,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    )
    {
        $this->userPointRepository = $userPointRepository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function store(array $data): JsonResponse
    {
        $userPoint = $this->createUserPointEntity($data);

        $errors = $this->validator->validate($userPoint);

        if (count($errors) > 0) {
            return JsonFormat::error('data type error, verify our inputs.',[], 404);
        }

        try {
            $now = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));

            $polygon = str_replace('POLYGON((','', $data['geom']);
            $polygon = str_replace('))','', $polygon);

            $this->userPointRepository->storeManually(parameters: [
                'municipal_id' => $data['municipal_id'],
                'longitude' => $data['longitude'],
                'latitude' => $data['latitude'],
                'polygon' => "LINESTRING($polygon)",
                'created_at' => $now->format('Y-m-d H:i:s'),
                'updated_at' => $now->format('Y-m-d H:i:s'),

            ]);
        } catch (Exception) {
            return JsonFormat::error(message: 'Oops, User Point not saved.', data: []);
        }

        return JsonFormat::success(message:'User Point was saved successfully.',data:[],code: 201);
    }

    /**
     * @throws Exception
     */
    public function update(int $id, array $data): JsonResponse
    {
        $userPoint = $this->getUserPoint($id);

        if (!$userPoint) {
            return JsonFormat::error('User Point not found.',[],404);
        }

        $errors = $this->validator->validate($userPoint);

        if (count($errors) > 0) {
            return JsonFormat::error('data type error, verify our inputs.');
        }

        try {
            $this->updateUserPoint($userPoint, $data);
            $this->entityManager->flush();
        } catch (Exception) {
            return JsonFormat::error(message: 'Oops, User Point not updated.', data: []);
        }

        return JsonFormat::success(message:'User Point was updated successfully.',data:[],code: 200);
    }

    public function delete(int $id): JsonResponse
    {
        $userPoint = $this->getUserPoint($id);

        if (!$userPoint) {
            return JsonFormat::error('User Point not found.',[],404);
        }

        try {
            $this->entityManager->remove($userPoint);
            $this->entityManager->flush();
        } catch (Exception) {
            return JsonFormat::error(message: 'Oops, User Point not deleted.', data: []);
        }

        return JsonFormat::success(message:'User Point was deleted successfully.',data:[],code: 200);
    }

    private function getUserPoint(int $id): ?UserPoint
    {
        return $this->entityManager->getRepository(UserPoint::class)->find($id);
    }

    /**
     * @throws Exception
     */
    private function updateUserPoint(UserPoint $userPoint, array $data): void
    {
        $now = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $now->format('Y-m-d H:i:s');

        if (isset($data['municipal_id'])) {
            $municipal = $this->entityManager->getRepository(MunicipalGeometry::class)->find($data['municipal_id']);
            $municipal ? $userPoint->setMunicipal($municipal) : $userPoint->getMunicipal();
        }
        if (isset($data['latitude'])) $userPoint->setLatitude($data['latitude']);
        if (isset($data['longitude'])) $userPoint->setLongitude($data['longitude']);
        if (isset($data['geom'])) $userPoint->setGeom($data['geom']);
        $userPoint->setCreatedAt($now);
        $userPoint->setUpdatedAt($now);
    }

    private function createUserPointEntity(array $data): UserPoint
    {
        if (!$data['municipal_id']) {
            throw new NotFoundHttpException('municipal_id is required.');
        }

        $municipalGeometry = $this->getMunicipalGeometry($data['municipal_id']);

        $userPoint = new UserPoint();
        $userPoint->setLatitude($data['latitude']);
        $userPoint->setLongitude($data['longitude']);
        $userPoint->setMunicipal($municipalGeometry);
        $userPoint->setGeom($data['geom']);

        return $userPoint;
    }

    private function getMunicipalGeometry(int $municipalId): ?MunicipalGeometry
    {
        $municipalGeometry = $this->entityManager->getRepository(MunicipalGeometry::class)->find($municipalId);

        if (!$municipalGeometry) {
            throw new NotFoundHttpException('MunicipalGeometry not found for the provided ID.');
        }

        return $municipalGeometry;
    }
}
