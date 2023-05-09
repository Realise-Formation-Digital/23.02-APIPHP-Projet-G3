<?php
    require_once __DIR__ . "/../models/IngredientsModel.php";

    class IngredientsController extends BaseController{

      /**
     * 
     */
    public function searchIngredients() {
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
  
          $ingredients = $ingredientsModel->searchIngredients($offset, $limit);
  
          $responseData = json_encode($ingredients);
  
          $this->sendOutput($responseData);
        } catch (Error $e) {
          $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
          $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
          $this->sendOutput($strErrorDesc, ['Content-Type: application/json', $strErrorHeader]);
        }
      }

      //Création de la deuxieme methode pour selectionner une biere selon son id
      public function readIngredients() {
        try {
          $ingredientsModel = new Ingredients();
  
          $urlParams = $this->getQueryStringParams();
          if (!isset($urlParams['id']) || !is_numeric($urlParams['id'])) {
            throw new Exception("L'identifiant est incorrect ou n'a pas été spécifié");
          }
  
          // TEST SI LA BIERE EXISTE 
          $ingredient = $ingredientsModel->readIngredients($urlParams['id']);
          if($ingredient == false){
            throw new Exception("L'ID rentré n'existe pas");
          }
  
          $responseData = json_encode($ingredient);
  
          $this->sendOutput($responseData);
        } catch (Error $e) {
          $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
          $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
          $this->sendOutput($strErrorDesc, ['Content-Type: application/json', $strErrorHeader]);
        }
      }

      public function createIngredients() {
        try {
          $ingredientsModel = new Ingredients();
  
          $body = $this->getBody();
          if (!$body) {
            throw new Exception("Aucune donnée n'a été transmise dans le formulaire");
          }

          $counter = count($body);

          // VERIFIE SI LES DONNEES ONT BIEN ETE RENTREES
          for($i = 0; $i < $counter-1; $i++){
            if (!isset($body[$i]['id'])) {
              throw new Exception("Aucun id n'a été spécifié");
            }
            if (!isset($body[$i]['type'])) {
              throw new Exception("Aucun type n'a été spécifié");
            }
            if (!isset($body[$i]['name'])) {
              throw new Exception("Aucun nom n'a été spécifié");
            }
            if (!isset($body[$i]['amount_value'])) {
              throw new Exception("Aucun valeur n'a été spécifié");
            }
            if (!isset($body[$i]['amount_unit'])) {
              throw new Exception("Aucune unité n'a été spécifiée");
            }
            if (!isset($body[$i]['amount_add'])) {
              throw new Exception("Aucun ajout n'a été spécifiée");
            }
            if (!isset($body[$i]['amount_attribute'])) {
              throw new Exception("Aucunes propriétées n'a été spécifié");
            }
            
            // DECOMPOSE LE TABLEAU POUR ENSUITE L'ENVOYER DANS LA BDD
            $keys = array_keys($body[$i]);
            $valuesToInsert = [];
            foreach($keys as $key) {
              if (in_array($key, ['id', 'name', 'tagline', 'first_brewed', 'description', 'image_url', 'brewers_tips', 'contributed_by'])) {
                $valuesToInsert[$key] = $body[$i][$key];
              }
            }
            // CRÉATION DE LA BIÈRE DANS LA BASE DE DONNÉES
            $ingredient = $ingredientsModel->createIngredients($valuesToInsert);
            // var_dump($ingredient);
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

      public function updateIngredients() {
        try {
          // Initialisation de l'instance
          $ingredientsModel = new Ingredients();
          
          $urlParams = $this->getQueryStringParams();
          if (!isset($urlParams['id']) || !is_numeric($urlParams['id'])) {
            throw new Exception("L'identifiant est incorrect ou n'a pas été spécifié");
          }
          
          // TEST SI LA BIERE EXISTE
          $ingredient = $ingredientsModel->readIngredients($urlParams['id']);
          if($ingredient == false){
            throw new Exception("L'ID rentré n'existe pas");
          }

          $body = $this->getBody();
          if (!$body) {
            throw new Exception("L'identifiant est incorrect ou n'a pas été spécifié");
          }
  
          $counter = count($body);

          // VERIFIE SI LES DONNEES ONT BIEN ETE RENTREES
          for($i = 0; $i < $counter; $i++){
            if (!isset($body[$i]['id'])) {
              throw new Exception("Aucun id n'a été spécifié");
            }
            if (!isset($body[$i]['type'])) {
              throw new Exception("Aucun type n'a été spécifié");
            }
            if (!isset($body[$i]['name'])) {
              throw new Exception("Aucun nom n'a été spécifié");
            }
            if (!isset($body[$i]['amount_value'])) {
              throw new Exception("Aucun valeur n'a été spécifié");
            }
            if (!isset($body[$i]['amount_unit'])) {
              throw new Exception("Aucune quantité d'unité n'a été spécifiée");
            }
            if (!isset($body[$i]['amount_add'])) {
              throw new Exception("Aucuns changment n'a été spécifiée");
            }
            if (!isset($body[$i]['amount_attribute'])) {
              throw new Exception("Aucunes att n'a été spécifié");
            }
            
            // DECOMPOSE LE TABLEAU POUR ENSUITE L'ENVOYER DANS LA BDD
            $keys = array_keys($body[$i]);
            $valuesToInsert = [];
            foreach($keys as $key) {
              if (in_array($key, ['id', 'name', 'tagline', 'first_brewed', 'description', 'image_url', 'brewers_tips', 'contributed_by'])) {
                $valuesToInsert[$key] = $body[$i][$key];
              }
            }
            
            // CRÉATION DE LA BIÈRE DANS LA BASE DE DONNÉES
            $ingredient = $ingredientsModel->updateIngredients($valuesToInsert, $urlParams['id']);
          }
    
          $responseData = json_encode(array(
            "status" => true,
            "success" => 200
            ));
  
         
          $this->sendOutput($responseData);
        } catch (Error $e) {
          
          $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
          $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
          $this->sendOutput($strErrorDesc, ['Content-Type: application/json', $strErrorHeader]);
        }
      }

      /**
     * 
     */
    public function deleteIngredients() {
      try {
        
        $ingredientsModel = new Ingredients();

        
        $urlParams = $this->getQueryStringParams();
        if (!isset($urlParams['id']) || !is_numeric($urlParams['id'])) {
          throw new Exception("L'identifiant est incorrect ou n'a pas été spécifié");
        }
        
        $responseData = json_encode($ingredientsModel->deleteIngredients($urlParams['id']));

        
        $this->sendOutput($responseData);
      } catch (Error $e) {
        
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        $this->sendOutput($strErrorDesc, ['Content-Type: application/json', $strErrorHeader]);
      }
    }

    }