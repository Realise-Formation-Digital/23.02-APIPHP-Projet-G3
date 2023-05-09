<?php

// Les methodes dont le routes a bsoin. Il relie ce fichier avec les autres controllrs
require_once __DIR__ . "/controllers/BaseController.php";
require_once __DIR__ . "/controllers/BeerController.php";
require_once __DIR__ . "/controllers/IngredientsController.php";

// Redirige les URL avec leur methodes respectives ----
$routes = [
  "/beers/list" => ['GET', 'BeerController', 'getBeers'],
  "/beers/get" => ['GET', 'BeerController', 'readBeers'],
  "/beers/add" => ['POST', 'BeerController', 'createBeers'],
  "/beers/update" => ['PUT', 'BeerController', 'updateBeers'],
  "/beers/remove" => ['DELETE', 'BeerController', 'deleteBeers'],

  "/ingredients/list" => ['GET', 'IngredientsController', 'getIngredients'],
  "/ingredients/get" => ['GET', 'IngredientsController', 'readIngredients'],
  "/ingredients/add" => ['POST', 'IngredientsController', 'createIngredients'],
  "/ingredients/update" => ['PUT', 'IngredientsController', 'updateIngredients'],
  "/ingredients/remove" => ['DELETE', 'IngredientsController', 'deleteIngredients'],
];