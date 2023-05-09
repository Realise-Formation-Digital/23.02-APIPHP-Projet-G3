<?php
    require_once __DIR__ . "/../models/IngredientsModel.php";

    class IngredientsController extends BaseController{

      /**
     * Création de la method search qui va venir nous donner les 50 premiers resultats
     */
    public function getIngredients() {
        try {
          //Initialisation de l'instance
          $ingredientsModel = new Ingredients();
          //On vient définir nos filtre pour les query
          $limit = 0;
          $urlParams = $this->getQueryStringParams();
          if (isset($urlParams['limit']) && is_numeric($urlParams['limit'])) {
            $limit = $urlParams['limit'];
          }
          
          //on place notre offset à 50 pour avoir les réultat de 1 à 50
          $offset = 50;
          $urlParams = $this->getQueryStringParams();
          //on vient ajouter un if afin de vérifier que les champs sois bien remplie à savoir le nombre de page dans l url le fait que la valeur sois bien un chiffre et que le page rechercher sois plus grande que 0
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
  
          $ingredients = $this->getBody();
          if (!$ingredients) {
            throw new Exception("Aucune donnée n'a été transmise dans le formulaire");
          }

          // VERIFIE SI LES DONNEES ONT BIEN ETE RENTREES
          foreach ($ingredients as $ingredient) {
            if (!isset($ingredient['id'])) {
              throw new Exception("Aucun id n'a été spécifié");
            }
            if (!isset($ingredient['type'])) {
              throw new Exception("Aucun type n'a été spécifié");
            }
            if (!isset($ingredient['name'])) {
              throw new Exception("Aucun nom n'a été spécifié");
            }
            if (!isset($ingredient['amount_value'])) {
              throw new Exception("Aucun valeur n'a été spécifié");
            }
            if (!isset($ingredient['amount_unit'])) {
              throw new Exception("Aucun unité n'a été spécifié");
            }
            if (!isset($ingredient['amount_add'])) {
              throw new Exception("Aucun quantité n'a été spécifié");
            }
            if (!isset($ingredient['amount_attribute'])) {
              throw new Exception("Aucun parametres n'a été spécifié");
            }
            

            // CRÉATION DE LA BIÈRE DANS LA BASE DE DONNÉES
            $ingredients = $ingredientsModel->createIngredients($ingredient);            
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
          
          // TEST SI L'INGREDIENT EXISTE
          $ingredients = $ingredientsModel->readIngredients($urlParams['id']);
          if($ingredients == false){
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
              if (in_array($key, ['id', 'type', 'name', 'amount_value', 'amount_unit', 'amount_add', 'amount_attribute',])) {
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