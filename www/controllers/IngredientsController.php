<?php
require_once __DIR__ . "/../models/IngredientsModel.php";

class IngredientsController extends BaseController{
    //method qui va nous donner tous les ingrédients
    public function getIngredients() {
        try {
          $ingredientsModel = new Ingredients();
  
          $limit = 10;
          $urlParams = $this->getQueryStringParams();
          if (isset($urlParams['limit']) && is_numeric($urlParams['limit'])) {
            $limit = $urlParams['limit'];
          }
  
          $offset = 0;
          $urlParams = $this->getQueryStringParams();
          if (isset($urlParams['page']) && is_numeric($urlParams['page']) && $urlParams['page'] > 0) {
            $offset = ($urlParams['page'] - 1) * $limit;
          }
  
          $ingredients = $ingredientsModel->searchBeers($offset, $limit);
  
          $responseData = json_encode($ingredients);
  
          $this->sendOutput($responseData);
        } catch (Error $e) {
          $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
          $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
          $this->sendOutput($strErrorDesc, ['Content-Type: application/json', $strErrorHeader]);
        }
    }
    //method qui va nous ressortir une biere via son ingrédients
    public function readIngredients() {
        try {
            $ingredientsModel = new Ingredients();
  
          $urlParams = $this->getQueryStringParams();
          if (!isset($urlParams['id']) || !is_numeric($urlParams['id'])) {
            throw new Exception("L'identifiant est incorrect ou n'a pas été spécifié");
          }
  
          // TEST SI LA BIERE EXISTE 
          $beer = $ingredientsModel->getObject($urlParams['id']);
          if($beer == false){
            throw new Exception("L'ID rentré n'existe pas");
          }
  
          $responseData = json_encode($beer);
  
          $this->sendOutput($responseData);
        } catch (Error $e) {
          $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
          $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
          $this->sendOutput($strErrorDesc, ['Content-Type: application/json', $strErrorHeader]);
        }
      }

      public function createBeers() {
        try {
            $ingredientsModel = new Ingredients();
  
          $body = $this->getBody();
          if (!$body) {
            throw new Exception("Aucune donnée n'a été transmise dans le formulaire");
          }

          $counter = count($body);

          // VERIFIE SI LES DONNEES ONT BIEN ETE RENTREES
          for($i = 0; $i < $counter; $i++){
            if (!isset($body[$i]['id'])) {
              throw new Exception("Aucun id n'a été spécifié");
            }
            if (!isset($body[$i]['type'])) {
              throw new Exception("Aucun nom n'a été spécifié");
            }
            if (!isset($body[$i]['name'])) {
              throw new Exception("Aucun tagline n'a été spécifié");
            }
            if (!isset($body[$i]['amount_value'])) {
              throw new Exception("Aucune valeur n'a été saisie");
            }
            if (!isset($body[$i]['amount_unit'])) {
              throw new Exception("Aucune quantité n'a été saisie");
            }
            if (!isset($body[$i]['amount_add'])) {
              throw new Exception("Aucune quantité n'a été ajouté");
            }
            if (!isset($body[$i]['amount_attribute'])) {
              throw new Exception("Aucuns attribut n'a été définit");
            }
            // DECOMPOSE LE TABLEAU POUR ENSUITE L'ENVOYER DANS LA BDD
            $keys = array_keys($body[$i]);
            $valuesToInsert = [];
            foreach($keys as $key) {
              if (in_array($key, ['id', 'type', 'name', 'amount_value', 'amount_unit', 'amount_add', 'amount_attribute'])) {
                $valuesToInsert[$key] = $body[$i][$key];
              }
            }
            // CRÉATION DE LA BIÈRE DANS LA BASE DE DONNÉES
            $ingredients = $ingredientsModel->insert($valuesToInsert);
            // var_dump($beer);
          }
          
          $responseData = json_encode(array(
            "statuts" => true,
            "success" => 200
            ));
          $this->sendOutput($responseData);
        } catch (Error $e) {
          // gestion des erreurs 
          $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
          $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
          $this->sendOutput($strErrorDesc, ['Content-Type: application/json', $strErrorHeader]);
        }
      }
}