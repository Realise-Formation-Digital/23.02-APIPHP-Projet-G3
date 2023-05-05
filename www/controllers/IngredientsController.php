<?php
require_once __DIR__ . "";

class IngredientsController extends BaseController{
    //Création de la fonction get

    public function getIngredients(){
        try {
            //instance d'un nouvelle objet Ingredient model pour intéragir avec la base de données et récuperer les données des ingrédients
            $ingredientModel = new IngredientModel();
            //on recupère les paramètres de la chaine de requête de l'url à partir d'objet this via la méthode getqueryParams()
            $urlParams = $this->getQueryStringParams();
            // Filtre par type d'ingrédient
            $filter = null;
            if (isset($urlParams['filter'])) {
            $filter = $urlParams['filter'];
            }
            // Tri par nom d'ingrédient
            $sort = null;
            if (isset($urlParams['sort']) && $urlParams['sort'] == ['name']) {
            $sort = 'name';
            }
            // Pagination
            $page = null;
            $perPage = null;
            if (isset($urlParams['page']) && isset($urlParams['per_page'])) {
            $page = $urlParams['page'];
            $perPage = $urlParams['per_page'];
            }
            // Récupération des ingrédients avec les filtres, tri et pagination
            $ingredients = $ingredientModel->searchIngredients($filter, $sort, $page, $perPage);
            // Envoi de la réponse en format json
            $responseData = json_encode($ingredients);
            $this->sendOutput($responseData);
            } catch (Error $e) {
            // En cas d'erreur
            $strErrorDesc = $e->getMessage() . ' Something went wrong! Please contact support.';
            $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            $this->sendOutput($strErrorDesc, ['Content-Type: application/json', $strErrorHeader]);
            }
        }

        public function readIngredients() {

        }

        public function createIngredients() {


        }

        public function updateIngredients(){

        }

        public function deleteIngredients() {

        }




    }