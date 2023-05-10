<?php
    require_once __DIR__ . "/../models/BeerModel.php";

    class BeerController extends BaseController{

            /**
     * 
     */
    public function getBeers() {
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
  
          // $beers = $beerModel->searchBeers($offset, $limit);
          $beers = $beerModel->searchBeers('Caramalt', 'name','page');
  
          $responseData = json_encode($beers);
  
          $this->sendOutput($responseData);
        } catch (Error $e) {
          $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
          $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
          $this->sendOutput($strErrorDesc, ['Content-Type: application/json', $strErrorHeader]);
        }
      }

      //Création de la deuxieme methode pour selectionner une biere selon son id
      public function readBeers() {
        try {
          $beerModel = new Beer();
  
          $urlParams = $this->getQueryStringParams();
          if (!isset($urlParams['id']) || !is_numeric($urlParams['id'])) {
            throw new Exception("L'identifiant est incorrect ou n'a pas été spécifié");
          }
  
          // TEST SI LA BIERE EXISTE 
          $beer = $beerModel->readBeers($urlParams['id']);
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
          $beerModel = new Beer();
          
          $body = $this->getBody();
          if (!$body) {
            throw new Exception("Aucune donnée n'a été transmise dans le formulaire");
          }
          // VERIFIE SI LES DONNEES ONT BIEN ETE RENTREES
          
            
            /*
            Gestion des erreurs pour les champs a remplir
            */
            if (!isset($body['name'])) {
              throw new Exception("Aucun nom n'a été spécifié");
            }
            if (!isset($body['tagline'])) {
              throw new Exception("Aucun tagline n'a été spécifié");
            }
            if (!isset($body['first_brewed'])) {
              throw new Exception("Aucun date n'a été spécifié");
            }
            if (!isset($body['description'])) {
              throw new Exception("Aucune description n'a été spécifiée");
            }
            if (!isset($body['image_url'])) {
              throw new Exception("Aucune image n'a été spécifiée");
            }
            if (!isset($body['brewers_tips'])) {
              throw new Exception("Aucuns conseils n'a été spécifié");
            }
            if (!isset($body['contributed_by'])) {
              throw new Exception("Aucune contribution n'a été spécifiée");
            }
            
            // DECOMPOSE LE TABLEAU POUR ENSUITE L'ENVOYER DANS LA BDD
            $keys = array_keys($body);
            $valuesToInsert = [];
            foreach($keys as $key) {
              if (in_array($key, ['id', 'name', 'tagline', 'first_brewed', 'description', 'image_url', 'brewers_tips', 'contributed_by'])) {
                $valuesToInsert[$key] = $body[$key];
              }
            }
            // CRÉATION DE LA BIÈRE DANS LA BASE DE DONNÉES
            $beer = $beerModel->createBeers($valuesToInsert);
            
          
          
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

      public function updateBeers() {
        try {
          // Initialisation de l'instance
          $beerModel = new Beer();
          
          $urlParams = $this->getQueryStringParams();
          if (!isset($urlParams['id']) || !is_numeric($urlParams['id'])) {
            throw new Exception("L'identifiant est incorrect ou n'a pas été spécifié");
          }
          
          // TEST SI LA BIERE EXISTE
          $beer = $beerModel->readBeers($urlParams['id']);
          if($beer == false){
            throw new Exception("L'ID rentré n'existe pas");
          }

          $body = $this->getBody();
          if (!$body) {
            throw new Exception("L'identifiant est incorrect ou n'a pas été spécifié");
          }  

          // VERIFIE SI LES DONNEES ONT BIEN ETE RENTREES
            if (!isset($body['name'])) {
              throw new Exception("Aucun nom n'a été spécifié");
            }
            if (!isset($body['tagline'])) {
              throw new Exception("Aucun tagline n'a été spécifié");
            }
            if (!isset($body['first_brewed'])) {
              throw new Exception("Aucun date n'a été spécifié");
            }
            if (!isset($body['description'])) {
              throw new Exception("Aucune description n'a été spécifiée");
            }
            if (!isset($body['image_url'])) {
              throw new Exception("Aucune image n'a été spécifiée");
            }
            if (!isset($body['brewers_tips'])) {
              throw new Exception("Aucuns conseils n'a été spécifié");
            }
            if (!isset($body['contributed_by'])) {
              throw new Exception("Aucune contribution n'a été spécifiée");
            }
            // DECOMPOSE LE TABLEAU POUR ENSUITE L'ENVOYER DANS LA BDD
            $keys = array_keys($body);
            $valuesToInsert = [];
            foreach($keys as $key) {
              if (in_array($key, ['id', 'name', 'tagline', 'first_brewed', 'description', 'image_url', 'brewers_tips', 'contributed_by'])) {
                $valuesToInsert[$key] = $body[$key];
              }
            }
            
            // CRÉATION DE LA BIÈRE DANS LA BASE DE DONNÉES
            $beer = $beerModel->updateBeers($valuesToInsert, $urlParams['id']);
          
    
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
     * methode pour delete les binchs
     */
    public function deleteBeers() {
      try {
        
        $beerModel = new Beer();

        
        $urlParams = $this->getQueryStringParams();
        if (!isset($urlParams['id']) || !is_numeric($urlParams['id'])) {
          throw new Exception("L'identifiant est incorrect ou n'a pas été spécifié");
        }
        
        $responseData = json_encode($beerModel->deleteBeers($urlParams['id']));

        
        $this->sendOutput($responseData);
      } catch (Error $e) {
        
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        $this->sendOutput($strErrorDesc, ['Content-Type: application/json', $strErrorHeader]);
      }
    }

    }