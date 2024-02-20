## About

TerraQ - Backend test. This test was created to assess your skills in developing a backend application
that integrates geospatial functionalities. The goal is to create an API that interacts with a
PostgreSQL database with the PostGIS extension, providing endpoints for the manipulation and querying
of geospatial data.
Created some items like Contracts, Seeders, Dependency Injection, Docker in Laravel 10.


## Objectives:
1) Install and Configure PostGIS on PostgreSQL;
2) Set up the PostgreSQL environment with PostGIS in Symfony migrations. This should be done to prepare the database for geospatial operations;
3) Create Geospatial Feature Tables

- Table **municipal_geometry**: To store the geometries of municipalities in São Paulo (SP) and Minas Gerais (MG).
  Municipalities should be created from the provided GeoJSONs. This table should have the fields "id," "name"
  (municipality name), and "geom" (geometry).
- Table **state_geometry**: To store the geometries of states São Paulo (SP) and Minas Gerais (MG).
  This table should be created by processing the data from each state's municipalities and performing
  a join/dissolve to form the state's geometry. This table should have the fields "id," "name" (state name),
  and "geom."
  Create the **state_geometry** and **municipal_geometry** tables using the **GeoJSONs** of São Paulo municipalities
  and the GeoJSONs of Minas Gerais municipalities as inputs.
- Table **pontos_usuario**: To store points posted by users, with fields for "id," "latitude," "longitude,"
  "municipal_id" (municipality ID), and "geom."
- PS: The geometry columns of the created tables should be of type Geometry, have the name "geom," and be in SRID 4326.
4) Develop Specific Endpoints:

- Location Query by Latitude and Longitude (/api/municipal/find): Endpoint to receive latitude and longitude and return the corresponding municipality or an error if not found.
- CRUD for Points (/api/user-point): Implement a complete CRUD to manipulate the "user_points" table, with the methods POST, GET, PUT, DELETE.

## Solution
Made using the Symfony 7 framework, PostgreSQL database with
PostGIS(PostGIS extends the capabilities of the PostgreSQL relational database
by adding support for storing, indexing, and querying geospatial data.) and
Docker for application containerization.

## How to run the project

After downloading the **geo-polygons-symfony** repository, being in its main folder, go up the structure composed of the following containers:

- **geo-polygons-symfony-back:** Composed with nginx and PHP, being exposed to port `9000`;
- **geo-polygons-postgres:** With the PostgreSQL database.

1) Through the following commands:
```sh 
docker-compose build --no-cache
```
2) After building the containers, execute the command below to start it.
```sh 
docker-compose up -d
```

After finishing the creation of the containers, we must execute the commands below so that the environment is ready to be used:

1. Execute the migrations with the command below to populate the database with the necessary tables of the solution:

```
docker exec -it geo-polygons-symfony-back php bin/console doctrine:migrations:migrate --no-interaction
```
2. Used to populate the tables (Required):
```
docker exec -it geo-polygons-symfony-back php bin/console doctrine:fixtures:load --no-interaction
```