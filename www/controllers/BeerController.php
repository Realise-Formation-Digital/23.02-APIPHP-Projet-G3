<?php
    require_once __DIR__ . "/../models/BeerModel.php";

    class BeerController extends BaseController{

            /**
     * 
     */
    public function getList() {
        try {
          $beerModel = new Beer();
  
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
  
          $beers = $beerModel->getAllBeers($offset, $limit);
  
          $responseData = json_encode($beers);
  
          $this->sendOutput($responseData);
        } catch (Error $e) {
          $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
          $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
          $this->sendOutput($strErrorDesc, ['Content-Type: application/json', $strErrorHeader]);
        }
      }

      //Création de la deuxieme methode pour selectionner une biere selon son id
      public function get() {
        try {
          $beerModel = new Beer();
  
          $urlParams = $this->getQueryStringParams();
          if (!isset($urlParams['id']) || !is_numeric($urlParams['id'])) {
            throw new Exception("L'identifiant est incorrect ou n'a pas été spécifié");
          }
  
          $beer = $beerModel->getSingleBeer($urlParams['id']);
  
          $responseData = json_encode($beer);
  
          $this->sendOutput($responseData);
        } catch (Error $e) {
          $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
          $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
          $this->sendOutput($strErrorDesc, ['Content-Type: application/json', $strErrorHeader]);
        }
      }

      public function store() {
        try {
          $beerModel = new Beer();
  
          $body = $this->getBody();
          if (!$body) {
            throw new Exception("Aucune donnée n'a été transmise dans le formulaire");
          }
  
          if (!isset($body['id'])) {
            throw new Exception("Aucun id n'a été spécifié");
          }
          if (!isset($body['name'])) {
            throw new Exception("Aucun nom n'a été spécifié");
          }
          if (!isset($body['tagline'])) {
            throw new Exception("Aucun tagline n'a été spécifié");
          }
          if (!isset($body['first_brewed'])) {
            throw new Exception("Aucun tagline n'a été spécifié");
          }
          if (!isset($body['description'])) {
            throw new Exception("Aucune description n'a été spécifié");
          }
          if (!isset($body['image_url'])) {
            throw new Exception("Aucune image n'a été spécifié");
          }
          if (!isset($body['brewers_tips'])) {
            throw new Exception("Aucuns conseils n'a été spécifié");
          }
          if (!isset($body['contribued_by'])) {
            throw new Exception("Aucune date n'a été spécifié");
          }
  
          $keys = array_keys($body);
          $valuesToInsert = [];
          foreach($keys as $key) {
            if (in_array($key, ['id', 'name', 'tagline', 'first_brewed', 'description', 'image_url', 'brewers_tips', 'contribued_by'])) {
              $valuesToInsert[$key] = $body[$key];
            }
          }
  
          $beer = $beerModel->insertBeer($valuesToInsert);
  
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

      public function update() {
        try {
          // Initialisation de l'instance
          $beerModel = new Beer();
  
          
          $body = $this->getBody();
          if (!$body) {
            throw new Exception("L'identifiant est incorrect ou n'a pas été spécifié");
          }
  
          // nous venons vérifier que le champ id sois bien remplis
          if (!isset($body['id'])) {
            throw new Exception("Aucun identifiant n'a été spécifié");
          }
  
          $keys = array_keys($body);
          $valuesToUpdate = [];
          foreach($keys as $key) {
            if (in_array($key, ['id', 'name', 'tagline', 'first_brewed', 'description', 'image_url', 'brewers_tips', 'contribued_by', 'food_pairing1', 'food_pairing2', 'food_pairing3'])) {
              $valuesToUpdate[$key] = $body[$key];
            }
          }
  
          
          $beer = $beerModel->updateBeer($valuesToUpdate, $body['id']);
  
    
          $responseData = json_encode(array(
            "statuts" => true,
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
    public function destroy() {
      try {
        
        $beerModel = new Beer();

        
        $urlParams = $this->getQueryStringParams();
        if (!isset($urlParams['id']) || !is_numeric($urlParams['id']) || !in_array($urlParams['id'])) {
          throw new Exception("L'identifiant est incorrect ou n'a pas été spécifié");
        }

        
        $beer = $beerModel->deleteBeer($urlParams['id']);

        
        $responseData = json_encode("L'utilisateur a été correctement supprimé");

        
        $this->sendOutput($responseData);
      } catch (Error $e) {
        
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        $this->sendOutput($strErrorDesc, ['Content-Type: application/json', $strErrorHeader]);
      }
    }

    }