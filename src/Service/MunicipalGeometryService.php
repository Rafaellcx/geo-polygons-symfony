<?php

namespace App\Service;

use App\Http\Helpers\JsonFormat;
use App\Repository\UserPointRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class MunicipalGeometryService
{
    private EntityManagerInterface $entityManager;
    private UserPointRepository $userPointRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPointRepository $userPointRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->userPointRepository = $userPointRepository;
    }

    public function find(array $fields): JsonResponse
    {
        $sql = "SELECT id, name, ST_AsText(geom) as geometry
                FROM municipal_geometry
                WHERE ST_Contains(geom, ST_SetSRID(ST_MakePoint(:longitude, :latitude), 4326)) = true
                LIMIT 1";

        $parameters = [
            'longitude' => $fields['longitude'],
            'latitude' => $fields['latitude']
        ];

        try{
            $nativeQuery = $this->entityManager->getConnection()->executeQuery($sql, $parameters);

            $result = $nativeQuery->fetchAssociative();
            if ($result) {
                $polygon = str_replace('POLYGON((','', $result['geometry']);
                $polygon = str_replace('))','', $polygon);

                $now = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));

                $parameters = [
                    'municipal_id' => $result['id'],
                    ...$parameters,
                    'polygon' => "LINESTRING($polygon)",
                    'created_at' => $now->format('Y-m-d H:i:s'),
                    'updated_at' => $now->format('Y-m-d H:i:s'),
                ];

                /*
                    Save the search in UserPoint, this way we can know how many searches
                    were realized for a determinate municipal
                */
                $this->userPointRepository->storeManually(parameters: $parameters);

                return new JsonResponse([
                    'id' => $result['id'],
                    'name' => $result['name'],
                    'geometry' => $result['geometry'],
                ]);
            } else {
                return JsonFormat::error('Oops, User Point not find.');
            }
        } catch (\Exception $e) {

            return JsonFormat::error('Oops, User Point not saved.');
        }
    }
}