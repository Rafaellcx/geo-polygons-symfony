<?php

namespace App\DataFixtures;

use App\Entity\MunicipalGeometry;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

class AppFixtures extends Fixture
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $filePaths = [
            'public/geo_files/geojs-35-mun.json',
            'public/geo_files/geojs-31-mun.json',
        ];

        foreach ($filePaths as $filePath) {
            $file = new File($filePath);
            if (!$file->isFile()) {
                return null;
            }
        }

        $file1 = json_decode(file_get_contents($filePaths[0]), true);
        $file2 = json_decode(file_get_contents($filePaths[1]), true);

        // Deletes all records if they exist to make a new import.
        $this->truncateMunicipalTable($this->entityManager);

        // Import the files to municipal_geometry table
        $this->importMunicipalFile(uf:'SP',municipals: $file1['features']);
        $this->importMunicipalFile(uf:'MG',municipals: $file2['features']);

        // Creates states from the previously imported municipalities.
        $this->createStatePolygon();
    }

    /**
     * @throws NonUniqueResultException
     * @throws Exception
     * @throws NoResultException
     */
    private function truncateMunicipalTable(EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(MunicipalGeometry::class);

        $count = $repository->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->getQuery()
            ->getSingleScalarResult();

        if ($count > 0) {
            $entityManager->createQuery('DELETE FROM ' . MunicipalGeometry::class)->execute();
            $entityManager->getConnection()->executeQuery('ALTER SEQUENCE municipal_geometry_id_seq RESTART WITH 1');
        }
    }

    /**
     * Dispatch the FetchMunicipalPolygonJob job.
     *
     * @param string $uf
     * @param array $municipals
     */
    private function importMunicipalFile(string $uf, array $municipals)
    {
        foreach ($municipals as $key => $value) {
            $coordinatesArray = $value['geometry']['coordinates'][0];
            $coordinatesString = '';

            foreach ($coordinatesArray as $coordinates) {
                $coordinatesString .= implode('# ', $coordinates) . '; ';
            }

            $coordinates = str_replace('#',' ', $coordinatesString);

            $this->formatMunicipalPolygon($uf,$value['properties']['name'],$coordinates);
        }
    }

    private function formatMunicipalPolygon(string $uf,string $name,string $coordinates)
    {
        $polygon = str_replace(',',' ', $coordinates);
        $polygon = str_replace(',',' ', $polygon);
        $polygon = str_replace(';',',', $polygon);
        $polygon = str_replace(', ',',', $polygon);

        if (str_ends_with($polygon, ',')) {
            // Remove the last comma from the string
            $polygon = substr($polygon, 0, -1);
        }

        $this->createMunicipalPolygon(uf: $uf,name: $name,polygon: $polygon);
    }

    private function createMunicipalPolygon(string $uf, string $name, string $polygon): void
    {
        $now = new \DateTime();

        $sql = "INSERT INTO municipal_geometry (name, geom, uf, created_at, updated_at) VALUES (:name, ST_MakePolygon(ST_GeomFromText(:polygon)), :uf, :createdAt, :updatedAt)";

        $parameters = [
            'name' => $name,
            'polygon' => "LINESTRING($polygon)",
            'uf' => $uf,
            'createdAt' => $now->format('Y-m-d H:i:s'),
            'updatedAt' => $now->format('Y-m-d H:i:s'),
        ];

        try {
            $this->entityManager->getConnection()->executeQuery($sql, $parameters);
        } catch (\Exception $e) {
            return;
        }

    }

    private function createStatePolygon()
    {
        try {
            $this->entityManager->getConnection()->executeQuery("INSERT INTO state_geometry (name, geom, created_at, updated_at)
                SELECT
                    CASE
                        WHEN uf = 'SP' THEN 'SÃO PAULO'
                        WHEN uf = 'MG' THEN 'MINAS GERAIS'
                        ELSE uf
                    END AS name,
                    ST_Union(geom) AS geom,
                    NOW() AS created_at,
                    NOW() AS updated_at
                FROM municipal_geometry
                WHERE uf IN ('SP', 'MG')
                GROUP BY uf");
        } catch (\Exception $e) {
            return;
        }
    }
}
