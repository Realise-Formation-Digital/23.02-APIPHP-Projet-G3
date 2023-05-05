<?php

  // Récupération des constantes d'accès pour la base de données
  require_once __DIR__ . "/config.php";

  // Liste des routes (endpoints) qui seront accessibles depuis le front-end
  require_once __DIR__ . "/routes.php";

  // Définit les en-tête d'origine, methods et autres pour éviter les soucis de CORS.
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    exit; // OPTIONS request wants only the policy, we can stop here
  }

  
  // recupere une partie de l'url (query param) 
  $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

  // si la query est pas existante ou que la bonne METHODE HTTP n'est pas rentrée on envoie un code d'erreur
  if (!isset($routes[$uri]) || $_SERVER['REQUEST_METHOD'] != $routes[$uri][0]) {
    header("HTTP/1.1 404 Not Found");
    exit();
  }

  // Récupère le nom de la classe du controleur
  $className = $routes[$uri][1];

  // Récupère le nom de la méthode
  $methodeName = $routes[$uri][2];

  // Crée un objet en fonction du nom du contrôleur
  $objController = new $className();

  // Fait appel à la méthode du contrôleur
  $objController->{$methodeName}();

?>
