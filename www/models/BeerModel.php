<?php

require_once __DIR__ . "/../models/DataBase.php";

/*-----création d'une class Beer pour faire des requettes dans la base de données ---*/
class Beer extends Database
{
    public $id;
    public $name;
    public $tagline;
    public $first_brewed;
    public $description;
    public $image_url;
    public $brewers_tips;
    public $contribued_by;
    public $food_pairin1;
    public $food_paring2;
    public $food_paring3;
  
/*----- la fonction recherche -----*/
  public function searchBeers($filter = '', $sort = '', $page = '', $per_page = '') {
    $options = '';
    if ($filter) {
      $options = "WHERE name=\"$filter\"";
    }
    if ($sort) {
      $options .= " ORDER BY $sort";
    }
    if ($page) {
      $options .= " WHERE $page";
    }
    if ($per_page) {
      $options .= " WHERE $per_page";
    }
    
    return $this->getObjects("SELECT * FROM beers $options");
  }
  // var_dump("SELECT * FROM beers $options");
  
/*----- la fonction create -----*/
  public function createBeers($beers) {
    unset($beers['id']);

    // ---- ajouter des éléments dans un tableau en string ----
    $keys = implode(", ", array_keys($beers));
    $values = '"' . implode('", "', array_values($beers)) . '"';


    return $this-> insert("INSERT INTO beers ($keys) VALUES ($values)", "SELECT * FROM beers");
  }
  
  /*----- la fonction read -----*/
  public function readBeers($id) {
    return $this-> getObject("SELECT * FROM beers WHERE id=$id");
  }

  /*----- la fonction update -----*/
  public function updateBeers ($beers,$id){
    //  CASSE LA LISTE DE TABLEAU ET STOCK DANS UNE LISTE ----
    $values_array = [];
    foreach($beers as $key => $value) {
      $values_array[] = "$key = \"$value\"";
    }
    $values = implode(",", array_values($values_array));

    // INJECTE LA LISTE DANS LA BASE DE DONNEE ----
    return $this->update(
      "UPDATE beers SET $values WHERE id = $id",
      "SELECT id FROM beers WHERE id=$id",
      "SELECT * FROM beers WHERE id=$id"
    );
  }
/*supprimer les bierres à l'aide de l'ID---*/
  public function deleteBeers($id){

    return $this -> delete("DELETE FROM beers WHERE id=$id",
    "SELECT id FROM beers WHERE id=$id");
  }
 
  }
?>