<?php
class Database{
   // class attributs
   protected $connect;

   /**
    * Class contructor - connect to the database API
    */
   public function __construct(){
      try{
         // try connecting to the database
         $this->connect = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE_NAME, DB_USERNAME, DB_PASSWORD);
      } catch(PDOException $e) {
         // send an error for there was an error connecting to the database
         throw new PDOException($e->getMessage());
      }
   }
   
   /**
   *    get several objects from database
   *    @param string $query
   *    return object
   */
   public function getObjects(string $query): array{
      try{
         // prepare statement
         $stmt = $this->connect->prepare($query);
         // execute the statement.
         $stmt->execute();
         // returns an anonymous object with property names that correspond to the column names returned in your result set 
         return $stmt->fetchAll(PDO::FETCH_OBJ);  
      
      } catch(PDOException $e) {
         // send an error for there was an error with the inserted query
         throw new PDOException($e->getMessage());
      }
   }

   /**
   *   get a single object from database
   *   @param string $query
   *   return object
   */
   public function getObject(string $query): bool|array{
      try{
         // prepare statement
         $stmt = $this->connect->prepare($query);
         // execute the statement.
         $stmt->execute();
         // returns an anonymous object with property names that correspond to the column names returned in your result set 
         // var_dump($stmt->fetch());
         return $stmt->fetch(PDO::FETCH_ASSOC); 
      } catch(PDOException $e){
         // send an error for there was an error with the inserted query
         throw new PDOException($e->getMessage());
      }
   }

   /**
   *   insert an object in the database
   *   @param string $query
   *   @param string $checkitem
   *   return object
   */
   public function insert(string $query, string $checkItem): stdClass {
      try{
         // execute the query.
         $this->connect->exec($query);
         // returns the last updated object in the database
         return $this->getObject($checkItem);
      } catch(PDOException $e){
         // send an error for there was an error with the inserted query
         throw new PDOException($e->getMessage());
      }
   }

   /**
   *   update an object in the database
   *   @param string $query
   *   @param string $checkitem
   *   return object
   */
   public function update(string $query, string $checkQuery, string $checkItem): bool|array{
      try{
         // Check if the entry exists before updating it
         if ($this->getObject($checkQuery)){
            // execute the query
            $this->connect->exec($query);
            // returns the last updated object in the database
            return $this->getObject($checkItem);
         } else {
            throw new Exception("Cannot update item because the item doesn't exist in the database");
         }
      } catch(PDOException $e){
         // send an error for there was an error with the inserted query
         throw new PDOException($e->getMessage());
      }
   }

    /**
   *   insert an object in the database
   *   @param string $query
   *   @param string $checkitem
   *   return object
   */
   public function delete(string $query, string $checkQuery): array{
      try{
         // Check if the entry exists before deleting it
         if ($this->getObject($checkQuery)){
            // execute the query
            $this->connect->exec($query);
            // returns a message saying the item has been deleted
            $message = [
               "Message" => "the item has been successfully deleted"
            ];
            return $message;
         } else {
            throw new Exception("Cannot delete item because the item doesn't exist in the database");
         }
      } catch(PDOException $e){
         // send an error for there was an error with the inserted query
         throw new PDOException($e->getMessage());
      }
   }

   /**
     * Add a n..m relation between two table.
     * 
     * @param string $tableName
     * @param string $id1Name
     * @param string $id2Name
     * @param string $id1Value
     * @param string $id2Value
     */
    public function addRelation($tableName, $id1Name, $id2Name, $id1Value, $id2Value)
    {
      try {
        // Prepare the query to the statement.
        $statement = $this->connect->prepare("SELECT * FROM $tableName WHERE $id1Name = '$id1Value' AND $id2Name = '$id2Value'");
        // Execute the statement.
        $statement->execute();
        // Get the result.
        $result = $statement->fetch();
        
        // Check if the relation does not exists before inserting it.
        if (!$result) {
          // Execute the query.
          $this->connect->exec("INSERT INTO $tableName ($id1Name, $id2Name) VALUES ('$id1Value', '$id2Value')");
        } else {
          throw new Exception("Cannot add relation because there is already a relation between these items");
        }
      } catch(PDOException $e) {
        // Send an error because something went wrong with the query.
        throw new Exception($e->getMessage());
      }
    }

    /**
     * Remove a n..m relation between two table.
     * 
     * @param string $tableName
     * @param string $idName
     * @param string $idValue
     */
    public function removeRelation($tableName, $idName, $idValue)
    {
      try {
        // Prepare the query to the statement.
        $statement = $this->connect->prepare("SELECT * FROM $tableName WHERE $idName = '$idValue'");
        // Execute the statement.
        $statement->execute();
        // Get the result.
        $result = $statement->fetch();
        
        // Check if the relation exists before delete it.
        if ($result) {
          // Execute the query.
          $this->connect->exec("DELETE FROM $tableName WHERE $idName = '$idValue'");
        } else {
          throw new Exception("Cannot delete relation because there are no relation between these items");
        }
      } catch(PDOException $e) {
        // Send an error because something went wrong with the query.
        throw new Exception($e->getMessage());
      }
    }

}