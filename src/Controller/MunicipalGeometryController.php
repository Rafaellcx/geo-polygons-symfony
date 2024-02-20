<?php

namespace App\Controller;

use App\Service\MunicipalGeometryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class MunicipalGeometryController extends AbstractController
{
    private MunicipalGeometryService $municipalGeometryService;

    /**
     * @param MunicipalGeometryService $municipalGeometryService
     */
    public function __construct(MunicipalGeometryService $municipalGeometryService)
    {
        $this->municipalGeometryService = $municipalGeometryService;
    }


    #[Route('/municipal/find', name: 'municipal-find',methods: ['POST'])]
    public function find(Request $request): JsonResponse
    {
        $longitude = $request->get('longitude') ?? null;
        $latitude = $request->get('latitude') ?? null;

        if ($longitude === null || $latitude === null) {
            return new JsonResponse(['error' => 'Longitude and/or latitude missing in the request.'], 400);
        }

        return $this->municipalGeometryService->find([
            'longitude' => $longitude,
            'latitude' => $latitude,
        ]);
    }
}
