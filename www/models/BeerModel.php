<?php

require_once "./DataBase.php";

/*-----création d'une class Beer pour faire des requettes dans la base de données ---*/
class Beer extends Database
{
  
/*----- la fonction recherche -----*/
  public function searchBeers() {
   return $this->getObjects("SELECT * FROM beers");
  }

  public function createBeers($beers) {
    // ---- ajouter des éléments dans un tableau en string ----
    $keys = implode(", ", array_keys($beers));
    $values = implode("', '", array_values($beers));

    return $this-> insert("INSERT INTO beers ($keys) VALUES ($values)", "SELECT * FROM beers");
  }
  
  public function readBeers($id) {
    return $this-> getObject("SELECT * FROM beers WHERE id=$id");
    
  }
  public function updatebeers ($beers,$id){
    // ---- faire une boucle pour executer l'ajout d'éléments au tableau ----
    $values_beers = [];
    foreach($beers as $key => $value) {
      $values_beers[] = "$key = '$value'";
    }
    $values = implode(",", array_values($values_beers));

    /*----- s'assurer que les bierres n'existe pas déjà pour l'ajouter à la liste de bierres----*/
    return $this-> update ("UPDATE beers SET $values WHERE id = $id",
    "SELECT id FROM beers WHERE id=$id",
    "SELECT * FROM beers WHERE id=$id"
  );

  }
/*-----supprimer les bierres à l'aide de l'ID---*/
  public function deleteBeers($beers,$id){

    return $this -> delete("DELETE FROM beers WHERE id=$id",
    "SELECT id FROM beers WHERE id=$id");
  }
 
  }
?>