<?php

require_once "./DataBase.php";

/*-----création d'une class ingredients pour chercher dans la base de données ---*/
class Ingredients extends Database
{
    public $id;
    public $type;
    public $name;
    public $amount_value;
    public $amount_unit;
    public $amount_add;
    public $amount_attribute;
    
  
/*----- la fonction recherche -----*/
  public function searchIngredients() {
   return $this->getObjects("SELECT * FROM ingredients");
  }

  public function createIngredients($ingredients) {
    // ---- ajouter des éléments dans un tableau en string ----
    $keys = implode(", ", array_keys($ingredients));
    $values = implode("', '", array_values($ingredients));

    return $this-> insert("INSERT INTO ingredients ($keys) VALUES ($values)", "SELECT * FROM ingredients");
  }
  
  public function readIngredients($id) {
    return $this-> getObject("SELECT * FROM ingredients WHERE id=$id");
    
  }
  public function updateIngredients ($ingredients,$id){
    // ---- faire une boucle pour executer l'ajout d'éléments au tableau ----
    $values_ingredients = [];
    foreach($ingredients as $key => $value) {
      $values_ingredients[] = "$key = '$value'";
    }
    $values = implode(",", array_values($values_ingredients));

    /*----- s'assurer que l'ingredients n'existe pas déjà pour l'ajouter à la liste d'ingredients----*/
    return $this-> update ("UPDATE ingredients SET $values WHERE id = $id",
    "SELECT id FROM ingredients WHERE id=$id",
    "SELECT * FROM ingredients WHERE id=$id"
  );

  }
/*-----supprimer les ingredients à l'aide de l'ID---*/
  public function deleteIngredients($id){

    return $this -> delete("DELETE FROM ingredients WHERE id=$id",
    "SELECT id FROM ingredients WHERE id=$id");
  }
 
  }
?>